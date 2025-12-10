<?php
header("Content-Type: application/json; charset=utf-8");
error_reporting(0);
session_start();
$action = $_GET['action'] ?? $_POST['action'] ?? 'get';
$me = $_SESSION['user']['username'] ?? null;
if (!$me){ http_response_code(401); echo json_encode(['error'=>'not logged']); exit; }


$storageDir = __DIR__ . '/../tmp/chats';
if (!file_exists($storageDir)) mkdir($storageDir, 0777, true);


function chatIdFor($a, $b){ $arr = [$a, $b]; sort($arr, SORT_STRING); return 'chat_'.preg_replace('/[^a-zA-Z0-9_\-]/','',implode('_',$arr)); }


if ($action === 'send'){
$data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
$to = $data['to'] ?? null;
$msg = trim($data['msg'] ?? '');
if (!$to || $msg===''){ echo json_encode(['error'=>'bad']); exit; }


$id = chatIdFor($me, $to);
$file = "$storageDir/{$id}.json";
$chat = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
$entry = ['from'=>$me,'to'=>$to,'msg'=>$msg,'time'=>time()];
$chat[] = $entry;
file_put_contents($file, json_encode($chat));
echo json_encode(['ok'=>true]);
exit;
}


if ($action === 'get'){
$other = $_GET['user'] ?? null;
if (!$other){ echo json_encode(['error'=>'no user']); exit; }
$id = chatIdFor($me, $other);
$file = "$storageDir/{$id}.json";
$chat = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
header('Content-Type: application/json');
echo json_encode($chat);
exit;
}


http_response_code(400);
echo json_encode(['error'=>'unknown action']);