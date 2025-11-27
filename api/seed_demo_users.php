<?php
// Seed demo users (run once) - creates users with hashed passwords
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

// Check if demo users already exist
$check = $conn->prepare('SELECT COUNT(*) as c FROM users WHERE email IN (?, ?, ?)');
if ($check) {
  $emails = ['filmmaker@cinda.com', 'mentor@cinda.com', 'sponsor@cinda.com'];
  $check->bind_param('sss', $emails[0], $emails[1], $emails[2]);
  $check->execute();
  $res = $check->get_result();
  $row = $res ? $res->fetch_assoc() : null;
  if ($row && intval($row['c']) > 0) {
    echo json_encode(['status' => 'skipped', 'message' => 'Demo users already exist', 'count' => intval($row['c'])], JSON_PRETTY_PRINT);
    exit;
  }
}

// Create demo users with hashed passwords and profile info
$demoUsers = [
  ['John Filmmaker', 'filmmaker@cinda.com', 'filmmaker123', 'Passionate filmmaker dedicated to telling authentic African stories', 'Independent Filmmaker', 'Kigali, Rwanda'],
  ['Sarah Mentor', 'mentor@cinda.com', 'mentor123', 'Award-winning cinematographer with 15+ years of experience in feature films', 'Senior Cinematographer', 'Los Angeles, CA'],
  ['Big Sponsor', 'sponsor@cinda.com', 'sponsor123', 'Supporting emerging talent in the African film industry', 'Film Producer & Investor', 'New York, NY']
];

$stmt = $conn->prepare('INSERT INTO users (name, email, password_hash, bio, title, location) VALUES (?, ?, ?, ?, ?, ?)');
if (!$stmt) {
  echo json_encode(['error' => 'Prepare failed'], JSON_PRETTY_PRINT);
  exit;
}

$inserted = 0;
foreach ($demoUsers as $user) {
  $hash = password_hash($user[2], PASSWORD_DEFAULT);
  $stmt->bind_param('ssssss', $user[0], $user[1], $hash, $user[3], $user[4], $user[5]);
  if ($stmt->execute()) $inserted++;
}

echo json_encode([
  'status' => 'ok',
  'message' => 'Demo users created successfully',
  'inserted' => $inserted,
  'users' => [
    ['email' => 'filmmaker@cinda.com', 'password' => 'filmmaker123'],
    ['email' => 'mentor@cinda.com', 'password' => 'mentor123'],
    ['email' => 'sponsor@cinda.com', 'password' => 'sponsor123']
  ]
], JSON_PRETTY_PRINT);
?>
