<?php
$conn=new mysqli('localhost','root','','cinda');
if($conn->connect_error) die(json_encode(['error'=>'DB failed']));

$id = isset($_GET['id']) ? $conn->real_escape_string($_GET['id']) : null;

if($id){
  $res = $conn->query("SELECT id,title,instructor,category,duration,level,description,image_url FROM courses WHERE id='".$id."' LIMIT 1");
  if(!$res){ header('Content-Type: application/json'); echo json_encode(['error'=>'Query failed']); exit; }
  $r = $res->fetch_assoc();
  if(!$r){ header('Content-Type: application/json'); echo json_encode(['error'=>'Not found']); exit; }
  $out = [
    '_id'=>$r['id'],'title'=>$r['title'],'instructor'=>['name'=>$r['instructor']],
    'category'=>$r['category'],'duration'=>$r['duration'],'level'=>$r['level'],
    'description'=>$r['description'],'image'=>$r['image_url']?:'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=400&q=80'
  ];
  header('Content-Type: application/json'); echo json_encode($out);
  exit;
}

$res=$conn->query("SELECT id,title,instructor,category,duration,level,description,image_url FROM courses ORDER BY id");
$out=[];
while($r=$res->fetch_assoc()) $out[]=[
  '_id'=>$r['id'],'title'=>$r['title'],'instructor'=>['name'=>$r['instructor']],
  'category'=>$r['category'],'duration'=>$r['duration'],'level'=>$r['level'],
  'description'=>$r['description'],'image'=>$r['image_url']?:'https://images.unsplash.com/photo-1485846234645-a62644f84728?w=400&q=80'
];
header('Content-Type: application/json'); echo json_encode($out);
?>
