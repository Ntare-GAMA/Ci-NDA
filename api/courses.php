<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');

$id = isset($_GET['id']) ? trim($_GET['id']) : null;

if ($id) {
  $stmt = $conn->prepare('SELECT id,title,instructor,category,duration,level,description,image_url FROM courses WHERE id = ? LIMIT 1');
  if (!$stmt) { echo json_encode(['error' => 'Query prepare failed']); exit; }
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $res = $stmt->get_result();
  $r = $res ? $res->fetch_assoc() : null;
  if (!$r) { echo json_encode(['error' => 'Not found']); exit; }
  $out = [
    '_id' => $r['id'],
    'title' => $r['title'],
    'instructor' => ['name' => $r['instructor']],
    'category' => $r['category'],
    'duration' => $r['duration'],
    'level' => $r['level'],
    'description' => $r['description'],
    'image' => $r['image_url'] ?: 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=400&q=80'
  ];
  echo json_encode($out);
  exit;
}

$stmt = $conn->prepare('SELECT id,title,instructor,category,duration,level,description,image_url FROM courses ORDER BY id');
if (!$stmt) { echo json_encode([]); exit; }
$stmt->execute();
$res = $stmt->get_result();
$out = [];
if ($res) {
  while ($r = $res->fetch_assoc()) {
    $out[] = [
      '_id' => $r['id'],
      'title' => $r['title'],
      'instructor' => ['name' => $r['instructor']],
      'category' => $r['category'],
      'duration' => $r['duration'],
      'level' => $r['level'],
      'description' => $r['description'],
      'image' => $r['image_url'] ?: 'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=400&q=80'
    ];
  }
}

echo json_encode($out);
?>
