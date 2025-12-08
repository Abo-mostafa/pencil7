<?php
// إعدادات الاتصال بقاعدة البيانات
$host = 'localhost';
$dbname = 'pencildb';
$username = 'root';
$password = '';


// $host = 'localhost';
// $dbname = 'elias_re1_pencildb';
// $username = 'elias_re1_hazem';
// $password = 'Hazem@39016h2';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "فشل الاتصال: " . $e->getMessage();
}
