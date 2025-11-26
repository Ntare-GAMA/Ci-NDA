<?php
$conn=new mysqli('localhost','root','','cinda');
if($conn->connect_error) die(json_encode(['error'=>'DB failed']));
  
/* mentors table assumed: id,name,title,bio,specialties,years,mentees,spots,avatar_url */
// support single mentor lookup via ?id
$id = isset($_GET['id']) ? $conn->real_escape_string($_GET['id']) : null;
$res=$conn->query("SELECT id,name,title,bio,specialties,years_mentoring mentees_count,spots_left,avatar_url FROM mentors" . ($id?" WHERE id='".$id."' LIMIT 1":" ORDER BY id"));
$fallbacks = [
  'https://images.unsplash.com/photo-1574267432644-f74f3e909713?w=200&q=80',
  'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&q=80',
  'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&q=80',
  'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&q=80',
  'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=200&q=80',
  'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=200&q=80'
];
$out=[];
$i=0;
while($r=$res->fetch_assoc()){
  $fallback = $fallbacks[$i % count($fallbacks)];
  $avatar = $r['avatar_url'] ? $r['avatar_url'] : $fallback;
  $item = [
    '_id'=>$r['id'],'name'=>$r['name'],'title'=>$r['title'],'bio'=>$r['bio'],
    'specialties'=>explode(',',$r['specialties']),'years'=>$r['years_mentoring'],
    'mentees'=>$r['mentees_count'],'spotsLeft'=>$r['spots_left'],
    'avatar'=>$avatar
  ];
  $out[] = $item;
  $i++;
  // if single lookup, return early the single item
  if($id){ header('Content-Type: application/json'); echo json_encode($item); exit; }
}
header('Content-Type: application/json'); echo json_encode($out);
?>
