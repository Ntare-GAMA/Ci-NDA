<?php
// Debug endpoint - lists users and sessions (local dev only)
require_once __DIR__ . '/db.php';

// Restrict access to localhost for safety
$remote = $_SERVER['REMOTE_ADDR'] ?? 'cli';
if (!in_array($remote, ['127.0.0.1','::1','::ffff:127.0.0.1']) && php_sapi_name() !== 'cli') {
  http_response_code(403);
  echo json_encode(['error' => 'Forbidden']);
  exit;
}

header('Content-Type: application/json');

try{
  $out = ['users' => [], 'sessions' => []];
  $res = $conn->query('SELECT id, name, email, created_at FROM users ORDER BY id DESC');
  if ($res) {
    while ($r = $res->fetch_assoc()) {
      $out['users'][] = $r;
    }
  }
  $res2 = $conn->query('SELECT id, user_id, token, created_at FROM sessions ORDER BY id DESC');
  if ($res2) {
    while ($r = $res2->fetch_assoc()) {
      $out['sessions'][] = $r;
    }
  }
  echo json_encode($out, JSON_PRETTY_PRINT);
}catch(Exception $e){
  http_response_code(500);
  echo json_encode(['error' => $e->getMessage()]);
}

?>
