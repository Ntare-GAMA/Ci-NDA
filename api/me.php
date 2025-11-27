<?php
require_once __DIR__ . '/db.php';

// Allow CORS from local dev origins if needed (adjust as required)
if (isset($_SERVER['HTTP_ORIGIN'])) {
  header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
  header('Access-Control-Allow-Credentials: true');
}

// Accept token via Authorization: Bearer <token>, cookie cinda_token, or JSON body { token }
$token = null;
$headers = getallheaders();
if (!empty($headers['Authorization'])) {
  if (preg_match('/Bearer\s+(\S+)/', $headers['Authorization'], $m)) $token = $m[1];
}
if (!$token && !empty($_COOKIE['cinda_token'])) $token = $_COOKIE['cinda_token'];
if (!$token) {
  $raw = file_get_contents('php://input');
  $in = json_decode($raw, true) ?: [];
  if (!empty($in['token'])) $token = $in['token'];
}

if (!$token) json_exit(['error' => 'No token provided'], 401);

$stmt = $conn->prepare('SELECT u.id, u.name, u.email, u.title, u.bio, u.location, u.avatar_url AS avatar FROM sessions s JOIN users u ON s.user_id = u.id WHERE s.token = ? LIMIT 1');
if (!$stmt) json_exit(['error' => 'Query failed'], 500);
$stmt->bind_param('s', $token);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
  json_exit($row);
}

json_exit(['error' => 'Invalid token or session not found'], 401);

?>
