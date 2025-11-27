<?php
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

// Delete existing mentors first
$conn->query('DELETE FROM mentors');

$mentors = [
  [
    'Sarah Mitchell',
    'Award-Winning Cinematographer',
    '15+ years of experience in feature films and documentaries. Specializing in natural lighting and atmospheric compositions. Emmy-nominated for documentary work.',
    'Cinematography,Lighting,Documentary',
    15,
    48,
    5,
    'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=200&q=80'
  ],
  [
    'James Chen',
    'Emmy-Nominated Editor',
    'Specialized in documentary and narrative editing. Worked on multiple award-winning films and series. Passionate about teaching storytelling through editing.',
    'Editing,Post-Production,Storytelling',
    12,
    35,
    3,
    'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80'
  ],
  [
    'Maria Rodriguez',
    'Acclaimed Independent Director',
    'Focused on developing new voices in independent cinema. Multiple festival awards and recognition for empowering emerging filmmakers globally.',
    'Directing,Independent Film,Creative Vision',
    10,
    28,
    2,
    'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&q=80'
  ],
  [
    'David Thompson',
    'Sound Designer for Major Productions',
    'Worked on Hollywood blockbusters and streaming platforms. Expert in creating immersive audio experiences that enhance storytelling and emotional impact.',
    'Sound Design,Audio Mixing,Post-Production',
    18,
    42,
    4,
    'https://images.unsplash.com/photo-1566492031773-4f4e44671857?w=200&q=80'
  ],
  [
    'Michael Okonkwo',
    'Screenwriter & Story Consultant',
    'Award-winning screenwriter with credits in feature films and TV series. Passionate about helping writers find their authentic voice and craft compelling narratives.',
    'Screenwriting,Story Development,Character Design',
    14,
    31,
    6,
    'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=200&q=80'
  ],
  [
    'Amara Nkrumah',
    'Producer & Film Financier',
    'Experienced in securing funding and managing film productions. Helps filmmakers navigate the business side of cinema and connect with investors worldwide.',
    'Production,Financing,Business Strategy',
    11,
    25,
    3,
    'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&q=80'
  ]
];

$ins = $conn->prepare('INSERT INTO mentors (name, title, bio, specialties, years_mentoring, mentees_count, spots_left, avatar_url) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
if (!$ins) {
  echo json_encode(['error' => 'Prepare failed']);
  exit;
}

$inserted = 0;
foreach ($mentors as $m) {
  $ins->bind_param('ssssiiis', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7]);
  if ($ins->execute()) $inserted++;
}

echo json_encode(['status' => 'ok', 'inserted' => $inserted]);
?>
