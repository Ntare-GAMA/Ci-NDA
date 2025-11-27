<?php
// Simple seeder for demo courses (works with current `db.php` connection)
require_once __DIR__ . '/db.php';
header('Content-Type: application/json');

function json_ok($d){ echo json_encode($d, JSON_PRETTY_PRINT); exit; }

// Delete existing courses first to reseed with all 12
$conn->query('DELETE FROM courses');

$samples = [
  ['Introduction to Cinematography','Roger Deakins','CINEMATOGRAPHY','12 weeks','Beginner','Master the fundamentals of camera work, lighting composition, and visual storytelling techniques used in professional film production.','https://images.unsplash.com/photo-1485846234645-a62644f84728?w=400&q=80'],
  ['Film Editing Masterclass','Thelma Schoonmaker','EDITING','8 weeks','Intermediate','Learn professional editing techniques, pacing, rhythm, and how to craft compelling narratives in post-production.','https://images.unsplash.com/photo-1574717024653-61fd2cf4d44d?w=400&q=80'],
  ['Directing Actors','Martin Scorsese','DIRECTING','10 weeks','Advanced','Discover how to communicate with actors, block scenes effectively, and bring out authentic performances on camera.','https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=400&q=80'],
  ['Sound Design for Film','Ben Burtt','SOUND DESIGN','6 weeks','Intermediate','Create immersive audio experiences through sound effects, foley, dialogue editing, and mixing techniques.','https://images.unsplash.com/photo-1598488035139-bdbb2231ce04?w=400&q=80'],
  ['Screenwriting Essentials','Aaron Sorkin','SCREENWRITING','14 weeks','Beginner','Craft compelling narratives, develop memorable characters, and master screenplay structure and dialogue.','https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400&q=80'],
  ['Advanced Color Grading','Walter Murch','POST-PRODUCTION','10 weeks','Advanced','Master color grading techniques, DaVinci Resolve workflows, and create stunning visual palettes for your films.','https://images.unsplash.com/photo-1611162617213-7d7a39e9b1d7?w=400&q=80'],
  ['Documentary Filmmaking','Werner Herzog','DOCUMENTARY','12 weeks','Intermediate','Learn the art of documentary storytelling, interview techniques, and how to capture authentic real-world narratives.','https://images.unsplash.com/photo-1478720568477-152d9b164e26?w=400&q=80'],
  ['Visual Effects Fundamentals','Dennis Muren','VFX','8 weeks','Beginner','Introduction to visual effects, compositing, green screen work, and basic CGI integration for modern filmmaking.','https://images.unsplash.com/photo-1635241161466-541f065683ba?w=400&q=80'],
  ['Music Composition for Film','Hans Zimmer','MUSIC','14 weeks','Advanced','Create powerful film scores, understand musical storytelling, and learn orchestration for cinema.','https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=400&q=80'],
  ['Production Design','Rick Carter','PRODUCTION','10 weeks','Intermediate','Master the art of creating immersive worlds through set design, props, and visual aesthetics.','https://images.unsplash.com/photo-1598899134739-24c46f58b8c0?w=400&q=80'],
  ['Lighting Techniques','Emmanuel Lubezki','CINEMATOGRAPHY','8 weeks','Advanced','Advanced lighting setups, natural light manipulation, and creating mood through illumination.','https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=400&q=80'],
  ['Film History & Theory','Martin Scorsese','THEORY','12 weeks','Beginner','Explore the evolution of cinema, influential movements, and critical analysis of landmark films.','https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=400&q=80']
];

$ins = $conn->prepare('INSERT INTO courses (title, instructor, category, duration, level, description, image_url) VALUES (?, ?, ?, ?, ?, ?, ?)');
if (!$ins) json_ok(['error' => 'Prepare failed', 'db_error' => method_exists($conn,'error') ? $conn->error() : '']);

$inserted = 0;
foreach ($samples as $s) {
  $ins->bind_param('sssssss', $s[0], $s[1], $s[2], $s[3], $s[4], $s[5], $s[6]);
  if ($ins->execute()) $inserted++;
}

json_ok(['status' => 'ok', 'inserted' => $inserted]);

?>
