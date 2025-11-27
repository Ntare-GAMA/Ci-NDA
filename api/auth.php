<?php
/* Authentication endpoint - checks users table */
require_once __DIR__ . '/db.php';

$in = json_decode(file_get_contents('php://input'), true) ?: [];
$email = trim($in['email'] ?? '');
$password = $in['password'] ?? '';

if (!$email || !$password) {
  json_exit(['error' => 'Email and password required'], 400);
}

// Lookup user
$stmt = $conn->prepare('SELECT id, name, email, password_hash FROM users WHERE email = ? LIMIT 1');
if (!$stmt) json_exit(['error' => 'Query failed'], 500);
$stmt->bind_param('s', $email);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
  // Verify password using password_verify for hashed passwords
  $ok = false;
  if (!empty($row['password_hash']) && function_exists('password_verify')) {
    $ok = password_verify($password, $row['password_hash']);
  }

  if ($ok) {
    // return a simple token (for production use JWT or server sessions)
    $token = bin2hex(random_bytes(16));
    // persist session token (sessions table exists in SQLite fallback)
    $created_at = date('Y-m-d H:i:s');
    if ($ins = $conn->prepare('INSERT INTO sessions (user_id, token, created_at) VALUES (?, ?, ?)')) {
      $ins->bind_param('iss', $row['id'], $token, $created_at);
      $ins->execute();
    }
    // set HttpOnly cookie for the token so front-end can authenticate via cookie
    if (!headers_sent()) {
      // PHP 7.3+ supports options array for setcookie
      if (PHP_VERSION_ID >= 70300) {
        setcookie('cinda_token', $token, [
          'expires' => time() + 60*60*24*7,
          'path' => '/',
          'httponly' => true,
          'samesite' => 'Lax'
        ]);
      } else {
        setcookie('cinda_token', $token, time() + 60*60*24*7, '/', '', false, true);
      }
    }
    json_exit(['token' => $token, 'user' => ['id' => $row['id'], 'name' => $row['name'], 'email' => $row['email']]]);
  }
}

json_exit(['error' => 'Invalid credentials'], 401);
?>
