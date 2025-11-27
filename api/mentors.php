<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// support single mentor lookup via ?id
$id = isset($_GET['id']) ? trim($_GET['id']) : null;

$fallbacks = [
  'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&q=80',
  'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80',
  'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&q=80',
  'https://images.unsplash.com/photo-1566492031773-4f4e44671857?w=200&q=80',
  'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&q=80',
  'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&q=80'
];

if ($id) {
  $stmt = $conn->prepare('SELECT id, name, title, bio, specialties, years_mentoring, mentees_count, spots_left, avatar_url FROM mentors WHERE id = ? LIMIT 1');
  if (!$stmt) {
    echo json_encode(['error' => 'Query prepare failed']);
    exit;
  }
  $stmt->bind_param('s', $id);
  $stmt->execute();
  $res = $stmt->get_result();
  $r = $res ? $res->fetch_assoc() : null;
  
  if (!$r) {
    echo json_encode(['error' => 'Mentor not found']);
    exit;
  }
  
  $fallback = $fallbacks[intval($r['id']) % count($fallbacks)];
  $avatar = $r['avatar_url'] ? $r['avatar_url'] : $fallback;
  
  $item = [
    '_id' => $r['id'],
    'id' => $r['id'],
    'name' => $r['name'],
    'title' => $r['title'],
    'bio' => $r['bio'],
    'specialties' => $r['specialties'] ? explode(',', $r['specialties']) : [],
    'years' => intval($r['years_mentoring']),
    'mentees' => intval($r['mentees_count']),
    'spotsLeft' => intval($r['spots_left']),
    'avatar' => $avatar
  ];
  
  echo json_encode($item);
  exit;
}

// Return all mentors
$stmt = $conn->prepare('SELECT id, name, title, bio, specialties, years_mentoring, mentees_count, spots_left, avatar_url FROM mentors ORDER BY id');
if (!$stmt) {
  echo json_encode([]);
  exit;
}

$stmt->execute();
$res = $stmt->get_result();
$out = [];
$i = 0;

if ($res) {
  while ($r = $res->fetch_assoc()) {
    $fallback = $fallbacks[$i % count($fallbacks)];
    $avatar = $r['avatar_url'] ? $r['avatar_url'] : $fallback;
    
    $item = [
      '_id' => $r['id'],
      'id' => $r['id'],
      'name' => $r['name'],
      'title' => $r['title'],
      'bio' => $r['bio'],
      'specialties' => $r['specialties'] ? explode(',', $r['specialties']) : [],
      'years' => intval($r['years_mentoring']),
      'mentees' => intval($r['mentees_count']),
      'spotsLeft' => intval($r['spots_left']),
      'avatar' => $avatar
    ];
    
    $out[] = $item;
    $i++;
  }
}

echo json_encode($out);
?>
