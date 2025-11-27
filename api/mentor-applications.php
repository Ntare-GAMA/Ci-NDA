<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Get all mentor applications or a specific one
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? trim($_GET['id']) : null;
    
    if ($id) {
        $stmt = $conn->prepare('SELECT * FROM mentor_applications WHERE id = ? LIMIT 1');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $application = $res ? $res->fetch_assoc() : null;
        
        if (!$application) {
            echo json_encode(['error' => 'Application not found']);
            exit;
        }
        
        echo json_encode($application);
    } else {
        $stmt = $conn->prepare('SELECT * FROM mentor_applications ORDER BY created_at DESC');
        $stmt->execute();
        $res = $stmt->get_result();
        $applications = [];
        
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $applications[] = $row;
            }
        }
        
        echo json_encode($applications);
    }
    exit;
}

// Update application status (approve/reject)
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['id']) || !isset($input['status'])) {
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }
    
    $id = $input['id'];
    $status = $input['status']; // 'approved' or 'rejected'
    $notes = isset($input['notes']) ? $input['notes'] : '';
    
    // Update application status
    $stmt = $conn->prepare('UPDATE mentor_applications SET status = ?, admin_notes = ?, reviewed_at = datetime("now") WHERE id = ?');
    $stmt->bind_param('sss', $status, $notes, $id);
    
    if ($stmt->execute()) {
        // If approved, create mentor entry
        if ($status === 'approved') {
            // Get application details
            $getStmt = $conn->prepare('SELECT * FROM mentor_applications WHERE id = ?');
            $getStmt->bind_param('s', $id);
            $getStmt->execute();
            $res = $getStmt->get_result();
            $app = $res->fetch_assoc();
            
            if ($app) {
                // Insert into mentors table
                $insertStmt = $conn->prepare('INSERT INTO mentors (name, title, bio, specialties, years_mentoring, mentees_count, spots_left, avatar_url) VALUES (?, ?, ?, ?, ?, 0, ?, ?)');
                $spots = 10; // Default spots
                $insertStmt->bind_param('ssssiis', $app['name'], $app['title'], $app['bio'], $app['specialties'], $app['years_experience'], $spots, $app['avatar_url']);
                $insertStmt->execute();
            }
        }
        
        echo json_encode(['success' => true, 'message' => 'Application ' . $status]);
    } else {
        echo json_encode(['error' => 'Failed to update application']);
    }
    exit;
}

echo json_encode(['error' => 'Invalid request method']);
?>
