<?php
// Debug endpoint: shows which DB backend is active and a short users list
// LOCAL DEVELOPMENT ONLY — do NOT enable in production.
require_once __DIR__ . '/db.php';

// helper
function ok($data) { header('Content-Type: application/json'); echo json_encode($data, JSON_PRETTY_PRINT); exit; }

$info = [ 'driver' => null, 'notes' => [] ];

// detect driver type
if (is_object($conn)) {
  $cls = get_class($conn);
  $info['driver'] = $cls;
  if ($cls === 'SQLiteConnCompat') {
    // try to locate sqlite file path from the PDO inside (reflection)
    $info['notes'][] = 'Using SQLite compatibility wrapper';
  } elseif ($cls === 'mysqli' || $conn instanceof mysqli) {
    $info['notes'][] = 'Using mysqli (MySQL) connection';
  } else {
    $info['notes'][] = "Unknown connection class: $cls";
  }
} else {
  $info['notes'][] = 'No $conn object available';
}

$users = [];
try {
  $stmt = $conn->prepare('SELECT id, name, email, created_at FROM users ORDER BY id DESC LIMIT 10');
  if ($stmt) {
    // bind_param may be no-op for wrapper; execute and fetch
    if (method_exists($stmt, 'bind_param')) {
      // no params to bind
    }
    $stmt->execute();
    $res = null;
    if (method_exists($stmt, 'get_result')) {
      $res = $stmt->get_result();
      while ($r = $res->fetch_assoc()) {
        $users[] = $r;
      }
    } else {
      // fallback: try query on connection
      $q = $conn->query('SELECT id, name, email, created_at FROM users ORDER BY id DESC LIMIT 10');
      if ($q) {
        while ($r = $q->fetch_assoc()) $users[] = $r;
      }
    }
  }
} catch (Exception $e) {
  $info['notes'][] = 'Query failed: ' . $e->getMessage();
}

ok(['info' => $info, 'users' => $users]);

?>
