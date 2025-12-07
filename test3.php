<?php
include 'hmb/conn.php';
date_default_timezone_set("Asia/Riyadh");
$today = date("Y-m-d");
echo $today;echo "<br>";
$sql="SELECT DATE(date)as date,teacher_id FROM `attendance_teacher`";
$stmt = $pdo->prepare($sql);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['date'];
    echo "<br>";

}