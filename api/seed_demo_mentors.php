<?php
require_once __DIR__ . '/db.php';

// Simple seeder for mentors table. Works with MySQL (mysqli) or SQLite compatibility in api/db.php
$mentors = [
  [
    'name'=>'Sarah Mitchell',
    'title'=>'Award-Winning Cinematographer',
    'bio'=>'15+ years of experience in feature films and documentaries. Specializing in natural lighting and atmospheric compositions. Emmy-nominated for documentary work.',
    'specialties'=>['Cinematography','Lighting','Documentary'],
    'years'=>15,
    'mentees'=>48,
    'spots'=>5,
    'avatar'=>'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=400&q=80'
  ],
  [
    'name'=>'James Chen',
    'title'=>'Emmy-Nominated Editor',
    'bio'=>'Specialized in documentary and narrative editing. Worked on multiple award-winning films and series. Passionate about teaching storytelling through editing.',
    'specialties'=>['Editing','Post-Production','Storytelling'],
    'years'=>12,
    'mentees'=>35,
    'spots'=>3,
    'avatar'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80'
  ],
  [
    'name'=>'Maria Rodriguez',
    'title'=>'Acclaimed Independent Director',
    'bio'=>'Focused on developing new voices in independent cinema. Multiple festival awards and recognition for empowering emerging filmmakers globally.',
    'specialties'=>['Directing','Independent Film','Creative Vision'],
    'years'=>10,
    'mentees'=>28,
    'spots'=>2,
    'avatar'=>'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=400&q=80'
  ],
  [
    'name'=>'David Thompson',
    'title'=>'Sound Designer for Major Productions',
    'bio'=>'Worked on Hollywood blockbusters and streaming platforms. Expert in creating immersive audio experiences that enhance storytelling and emotional impact.',
    'specialties'=>['Sound Design','Audio Mixing','Post-Production'],
    'years'=>18,
    'mentees'=>42,
    'spots'=>4,
    'avatar'=>'https://images.unsplash.com/photo-1566492031773-4f4e44671857?w=400&q=80'
  ],
  [
    'name'=>'Michael Okonkwo',
    'title'=>'Screenwriter & Story Consultant',
    'bio'=>'Award-winning screenwriter with credits in feature films and TV series. Passionate about helping writers find their authentic voice and craft compelling narratives.',
    'specialties'=>['Screenwriting','Story Development','Character Design'],
    'years'=>14,
    'mentees'=>31,
    'spots'=>6,
    'avatar'=>'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=400&q=80'
  ],
  [
    'name'=>'Amara Nkrumah',
    'title'=>'Producer & Film Financier',
    'bio'=>'Experienced in securing funding and managing film productions. Helps filmmakers navigate the business side of cinema and connect with investors worldwide.',
    'specialties'=>['Production','Financing','Business Strategy'],
    'years'=>11,
    'mentees'=>25,
    'spots'=>3,
    'avatar'=>'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=400&q=80'
  ]
];

$insertSql = "INSERT INTO mentors (name,title,bio,specialties,years_mentoring,mentees_count,spots_left,avatar_url) VALUES (?,?,?,?,?,?,?,?)";
$stmt = $conn->prepare($insertSql);
if(!$stmt){
  echo json_encode(['error'=>'prepare_failed','message'=>isset($conn->error)?$conn->error:'']);
  exit(1);
}

$inserted = [];
foreach($mentors as $m){
  $name = $m['name'];
  $title = $m['title'];
  $bio = $m['bio'];
  $specialties = implode(',', $m['specialties']);
  $years = (int)$m['years'];
  $mentees = (int)$m['mentees'];
  $spots = (int)$m['spots'];
  $avatar = $m['avatar'];

  // bind_param expects variables by reference; types: s s s s i i i s => 'ssssiiis'
  if(method_exists($stmt,'bind_param')){
    $stmt->bind_param('ssssiiis', $name, $title, $bio, $specialties, $years, $mentees, $spots, $avatar);
    $ok = $stmt->execute();
  } else {
    // Fallback for very simple drivers (shouldn't happen with provided compat)
    $ok = $conn->query($insertSql);
  }

  if(!$ok){
    // try to continue but record error
    $inserted[] = ['name'=>$name,'status'=>'error','error'=>method_exists($stmt,'error')?$stmt->error(): (isset($conn->error)?$conn->error:'unknown')];
    continue;
  }

  // get last id
  $lastId = null;
  if(isset($conn->insert_id) && $conn->insert_id){ $lastId = $conn->insert_id; }
  elseif(method_exists($conn,'lastInsertId')){ $lastId = $conn->lastInsertId(); }
  $inserted[] = ['name'=>$name,'id'=>$lastId,'status'=>'ok'];
}

header('Content-Type: application/json');
echo json_encode(['inserted'=>$inserted]);

?>
