<?php
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $in = json_decode(file_get_contents('php://input'), true) ?: [];
  $title = trim($in['title'] ?? '');
  $owner = trim($in['owner'] ?? '');
  $description = trim($in['description'] ?? '');
  $category = trim($in['category'] ?? '');
  $tags = is_array($in['tags']) ? implode(',', $in['tags']) : trim($in['tags'] ?? '');
  $thumbnail = trim($in['thumbnail'] ?? '');

  if (!$title || !$owner) json_exit(['error' => 'title and owner required'], 400);

  $stmt = $conn->prepare('INSERT INTO portfolios (title, owner, description, category, tags, thumbnail_url) VALUES (?, ?, ?, ?, ?, ?)');
  if (!$stmt) json_exit(['error' => 'Prepare failed'], 500);
  $stmt->bind_param('ssssss', $title, $owner, $description, $category, $tags, $thumbnail);
  if ($stmt->execute()) {
    $lastId = null;
    if (method_exists($conn, 'lastInsertId')) {
      try { $lastId = $conn->lastInsertId(); } catch (Exception $e) { $lastId = null; }
    }
    if (!$lastId && property_exists($conn, 'insert_id')) {
      $lastId = $conn->insert_id ?: null;
    }
    json_exit(['msg' => 'created', 'id' => $lastId], 201);
  } else json_exit(['error' => 'Insert failed'], 500);
}

$res = $conn->query("SELECT id,title,owner,description,category,tags,views,likes,thumbnail_url FROM portfolios ORDER BY id DESC");
$out = [];
while ($r = $res->fetch_assoc()) $out[] = [
  '_id' => $r['id'], 'title' => $r['title'], 'owner' => $r['owner'], 'description' => $r['description'],
  'category' => $r['category'], 'tags' => $r['tags'], 'views' => $r['views'], 'likes' => $r['likes'],
  'thumbnail' => $r['thumbnail_url'] ?: 'https://images.unsplash.com/photo-1478720568477-152d9b164e26?w=600&q=80'
];
header('Content-Type: application/json'); echo json_encode($out);
?>
