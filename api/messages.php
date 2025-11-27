<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// Get messages
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $userId = isset($_GET['user_id']) ? trim($_GET['user_id']) : null;
    $conversationWith = isset($_GET['with']) ? trim($_GET['with']) : null;
    
    if ($userId && $conversationWith) {
        // Get conversation between two users
        $stmt = $conn->prepare('SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY created_at ASC');
        $stmt->bind_param('ssss', $userId, $conversationWith, $conversationWith, $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $messages = [];
        
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $messages[] = $row;
            }
        }
        
        echo json_encode($messages);
    } elseif ($userId) {
        // Get all conversations for user
        $stmt = $conn->prepare('SELECT DISTINCT 
            CASE 
                WHEN sender_id = ? THEN receiver_id 
                ELSE sender_id 
            END as conversation_with,
            MAX(created_at) as last_message_time
            FROM messages 
            WHERE sender_id = ? OR receiver_id = ?
            GROUP BY conversation_with
            ORDER BY last_message_time DESC');
        $stmt->bind_param('sss', $userId, $userId, $userId);
        $stmt->execute();
        $res = $stmt->get_result();
        $conversations = [];
        
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                $conversations[] = $row;
            }
        }
        
        echo json_encode($conversations);
    } else {
        echo json_encode(['error' => 'Missing user_id parameter']);
    }
    exit;
}

// Send message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($input['sender_id']) || !isset($input['receiver_id']) || !isset($input['message'])) {
        echo json_encode(['error' => 'Missing required fields']);
        exit;
    }
    
    $senderId = $input['sender_id'];
    $receiverId = $input['receiver_id'];
    $message = $input['message'];
    $isRead = 0;
    
    $stmt = $conn->prepare('INSERT INTO messages (sender_id, receiver_id, message, is_read, created_at) VALUES (?, ?, ?, ?, datetime("now"))');
    $stmt->bind_param('sssi', $senderId, $receiverId, $message, $isRead);
    
    if ($stmt->execute()) {
        $messageId = method_exists($conn, 'insert_id') ? $conn->insert_id : $conn->lastInsertId();
        echo json_encode(['success' => true, 'message_id' => $messageId]);
    } else {
        echo json_encode(['error' => 'Failed to send message']);
    }
    exit;
}

echo json_encode(['error' => 'Invalid request method']);
?>
