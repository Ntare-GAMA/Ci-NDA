<?php
require_once __DIR__ . '/db.php';

$in = json_decode(file_get_contents('php://input'), true) ?: [];
$name = trim($in['name'] ?? '');
$email = trim($in['email'] ?? '');
$password = $in['password'] ?? '';

if (!$name || !$email || !$password) {
  json_exit(['error' => 'Name, email and password are required'], 400);
}

// check existing
$check = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
if (!$check) json_exit(['error' => 'Query failed'], 500);
$check->bind_param('s', $email);
$check->execute();
$res = $check->get_result();
if ($res && $res->fetch_assoc()) {
  json_exit(['error' => 'User already exists'], 409);
}

// create user with password hash
$hash = function_exists('password_hash') ? password_hash($password, PASSWORD_DEFAULT) : $password;
// prepare and execute insert in a driver-agnostic way
$ins = $conn->prepare('INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)');
if (!$ins) {
  // try to gather any available error message from mysqli or PDO wrappers
  $err = '';
  if (is_object($conn)) {
    if (method_exists($conn, 'error')) { try{ $err = $conn->error(); } catch(Exception $e){} }
    elseif (property_exists($conn, 'error')) { $err = $conn->error; }
  }
  json_exit(['error' => 'Insert prepare failed', 'db_error' => $err], 500);
}

$ins->bind_param('sss', $name, $email, $hash);
$ok = $ins->execute();
if (!$ok) {
  // gather statement-level error if available
  $serr = '';
  if (is_object($ins)) {
    if (method_exists($ins, 'error')) { try{ $serr = $ins->error(); } catch(Exception $e){} }
    elseif (property_exists($ins, 'error')) { $serr = $ins->error; }
  }
  json_exit(['error' => 'Failed to create user', 'db_error' => $serr], 500);
}

$id = null;
// Try to get last insert id from PDO wrapper first, then mysqli-style property
if (is_object($conn) && method_exists($conn, 'lastInsertId')) {
  try { $id = $conn->lastInsertId(); } catch (Exception $e) { $id = null; }
}
// mysqli insert_id fallback
if (!$id) {
  if (is_object($conn) && property_exists($conn, 'insert_id')) {
    $id = $conn->insert_id ?: null;
  }
}

// create session token and persist to sessions table
$token = bin2hex(random_bytes(16));
$created_at = date('Y-m-d H:i:s');
if ($s = $conn->prepare('INSERT INTO sessions (user_id, token, created_at) VALUES (?, ?, ?)')) {
  $s->bind_param('iss', $id, $token, $created_at);
  $s->execute();
}

// set HttpOnly cookie for convenience
if (!headers_sent()) {
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

json_exit(['message' => 'User created', 'token' => $token, 'user' => ['id' => $id, 'name' => $name, 'email' => $email]], 201);

?>
