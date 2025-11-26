<?php
require_once __DIR__ . '/db.php';

$data = json_decode(file_get_contents('php://input'), true) ?: [];
$courseId = $data['courseId'] ?? null;
$userId = $data['userId'] ?? null; // optional if client can provide
$userEmail = $data['email'] ?? null; // fallback

if (!$courseId) json_exit(['error' => 'courseId required'], 400);

// If userId not provided but email is, try to resolve userId
if (!$userId && $userEmail) {
	$s = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
	$s->bind_param('s', $userEmail);
	$s->execute();
	$r = $s->get_result()->fetch_assoc();
	if ($r) $userId = $r['id'];
}

// Basic insert into enrollments table
$stmt = $conn->prepare('INSERT INTO enrollments (user_id, course_id, created_at) VALUES (?, ?, NOW())');
if (!$stmt) json_exit(['error' => 'Failed to prepare insert'], 500);
$uid = $userId ? (int)$userId : null;
$cid = (int)$courseId;
$stmt->bind_param('ii', $uid, $cid);
if ($stmt->execute()) {
	json_exit(['msg' => 'enrolled', 'enrollmentId' => $conn->insert_id]);
} else {
	json_exit(['error' => 'Enroll failed'], 500);
}
?>
