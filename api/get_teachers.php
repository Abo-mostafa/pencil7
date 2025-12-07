<?php
ini_set('display_errors', 0);
header('Content-Type: application/json');
require_once "../hmb/conn.php";
date_default_timezone_set("Asia/Riyadh");
$today = date("Y-m-d");

$sql = "
SELECT 
    t.teacher_id,
    t.name,
    t.phone,
    t.subjects_ids,

    -- Subjects names
    (
        SELECT GROUP_CONCAT(subject_name SEPARATOR ' - ')
        FROM subjects
        WHERE FIND_IN_SET(subject_id, t.subjects_ids)
    ) AS subjects_name,

    -- Last presence today
    (SELECT date FROM attendance_teacher 
     WHERE teacher_id = t.teacher_id 
     AND state = 'presence'
     AND DATE(date) = :day
     ORDER BY id DESC LIMIT 1) AS attendance_time,

    -- Last leave today
    (SELECT date FROM attendance_teacher 
     WHERE teacher_id = t.teacher_id 
     AND state = 'withdrawal'
     AND DATE(date) = :day
     ORDER BY id DESC LIMIT 1) AS leave_time,

    -- Absence reason today
    (SELECT description FROM attendance_teacher 
     WHERE teacher_id = t.teacher_id 
     AND state = 'absence'
     AND DATE(date) = :day
     ORDER BY id DESC LIMIT 1) AS absence_description

FROM teachers t
ORDER BY t.name ASC
";

$stmt = $pdo->prepare($sql);
$stmt->execute(["day" => $today]);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($rows as &$t) {
    if ($t["attendance_time"] && $t["leave_time"]) {
        $t["attendance_status"] = "with_leave"; 
    } 
    else if ($t["attendance_time"]) {
        $t["attendance_status"] = "presence";
    }
    else if ($t["absence_description"]) {
        $t["attendance_status"] = "absence";
    }
    else {
        $t["attendance_status"] = "none";
    }
}

echo json_encode($rows);
