<?php
// InfinityFree Production Configuration

$DB_HOST = 'localhost';
$DB_USER = 'if0_40536602';
$DB_PASS = 'KwyDn3XXG6PQfOy';
$DB_NAME = 'if0_40536602_if0_40536602_cinda';

// Helper function
function json_exit($data, $code = 200) {
  http_response_code($code);
  header('Content-Type: application/json');
  echo json_encode($data);
  exit;
}

// Try connection without specifying port (let it use default socket)
$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);

if (!$conn) {
  $error = mysqli_connect_error();
  if (php_sapi_name() !== 'cli' && !headers_sent()) {
    json_exit(['error' => 'Database connection failed: ' . $error], 500);
  }
  die('DB Connection Error: ' . $error);
}

mysqli_set_charset($conn, 'utf8mb4');
?>
