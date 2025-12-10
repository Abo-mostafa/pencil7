
<?php
session_start();
$method = $_SERVER['REQUEST_METHOD'];
$storage = __DIR__ . '/../tmp/online.json';
if (!file_exists(dirname($storage))) mkdir(dirname($storage), 0777, true);
if (!file_exists($storage)) file_put_contents($storage, json_encode([]));


function safe_read($f){ $c = @file_get_contents($f); return $c ? json_decode($c, true) : []; }
function safe_write($f, $data){ file_put_contents($f, json_encode($data)); }


if ($method === 'POST'){
// تسجيل heartbeat
$user = $_SESSION['user']['username'] ?? null;
if (!$user){ http_response_code(401); echo json_encode(['error'=>'not logged']); exit; }


$online = safe_read($storage);
$now = time();
$online[$user] = ['users'=>$user, 'last'=>$now];
safe_write($storage, $online);
echo json_encode(['ok'=>true]);
exit;
}


// GET -> إرجاع قائمة المتصلين
$online = safe_read($storage);
$cut = time() - 20; // من يعتبر غير نشط بعد 20 ثانية
foreach ($online as $u => $info){ if ($info['last'] < $cut) unset($online[$u]); }
// نحذف نفس المستخدم من القائمة عند الإرجاع؟ لا، نُرجع الجميع لكن UI ممكن يستبعد نفسه
header('Content-Type: application/json');
echo json_encode(array_values($online));