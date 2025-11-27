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

// Get all mentorship requests or a specific one
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = isset($_GET['id']) ? trim($_GET['id']) : null;
    
    if ($id) {
        $stmt = $conn->prepare('SELECT * FROM mentorship_requests WHERE id = ? LIMIT 1');
        $stmt->bind_param('s', $id);
        $stmt->execute();
        $res = $stmt->get_result();
        $request = $res ? $res->fetch_assoc() : null;
        
        if (!$request) {
            echo json_encode(['error' => 'Request not found']);
            exit;
        }
        
        echo json_encode($request);
    } else {
        $stmt = $conn->prepare('SELECT * FROM mentorship_requests ORDER BY created_at DESC');
        $stmt->execute();
        $res = $stmt->get_result();
        $requests = [];
        
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $requests[] = $row;
            }
        }
        
        echo json_encode($requests);
    }
    exit;
}

// Update request status
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['id']) || !isset($input['status'])) {
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }
    
    $id = $input['id'];
    $status = $input['status']; // 'approved', 'rejected', 'active', 'completed'
    $notes = isset($input['notes']) ? $input['notes'] : '';
    
    $stmt = $conn->prepare('UPDATE mentorship_requests SET status = ?, admin_notes = ?, reviewed_at = datetime("now") WHERE id = ?');
    $stmt->bind_param('sss', $status, $notes, $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Request ' . $status]);
    } else {
        echo json_encode(['error' => 'Failed to update request']);
    }
    exit;
}

echo json_encode(['error' => 'Invalid request method']);
?>
