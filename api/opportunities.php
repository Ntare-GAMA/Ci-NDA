<?php
$conn=new mysqli('localhost','root','','cinda');
if($conn->connect_error) die(json_encode(['error'=>'DB failed']));
$res=$conn->query("SELECT id,title,org,type,description,funding,location,deadline FROM opportunities WHERE deadline>=CURDATE() ORDER BY deadline");
$out=[];
while($r=$res->fetch_assoc()) $out[]=[
  '_id'=>$r['id'],'title'=>$r['title'],'org'=>$r['org'],'type'=>$r['type'],'description'=>$r['description'],
  'funding'=>$r['funding'],'location'=>$r['location'],'deadline'=>$r['deadline']
];
header('Content-Type: application/json'); echo json_encode($out);
?>
