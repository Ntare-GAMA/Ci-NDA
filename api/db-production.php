<?php
header('Content-Type: application/json');
// InfinityFree Production MySQL Configuration
// When deploying to InfinityFree, rename this to db.php

$DB_DRIVER = 'mysql';
$DB_HOST = 'localhost';
$DB_USER = 'if0_40536602';
$DB_PASS = 'KwyDn3XXG6PQfOy';
$DB_NAME = 'if0_40536602_if0_40536602_cinda';
$DB_PORT = 3306;

// helper to send JSON and exit
function json_exit($data, $code = 200) {
  http_response_code($code);
  echo json_encode($data);
  exit;
}

// Connect to MySQL
$conn = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, (int)$DB_PORT);
if ($conn && !$conn->connect_error) {
  $conn->set_charset('utf8mb4');
  return; // $conn is available to requiring scripts
} else {
  json_exit(['error' => 'Failed to connect to MySQL: ' . ($conn ? $conn->connect_error : 'Connection failed')], 500);
}
?>
