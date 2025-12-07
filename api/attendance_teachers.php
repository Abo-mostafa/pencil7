<?php
header("Content-Type: application/json");
require_once "../hmb/conn.php";

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$action = $_GET["action"] ?? "";
$username = $_SESSION["user"]["username"] ?? "Unknown";

// قراءة الـ JSON المرسل
$raw = file_get_contents("php://input");
$data = json_decode($raw, true);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON"]);
    exit;
}

$teacher_id  = $data["teacher_id"]  ?? null;
$description = $data["description"] ?? "";

if (!$teacher_id) {
    echo json_encode(["status" => "error", "message" => "Missing teacher_id"]);
    exit;
}

try {

    switch ($action) {

        // ----------------------------
        //        حضور
        // ----------------------------
        case "presence":

            $desc = "Presence recorded by: $username";

            $stmt = $pdo->prepare("
                INSERT INTO attendance_teacher (teacher_id, state, date, description)
                VALUES (:id, 'presence', NOW(), :d)
            ");

            $stmt->execute([
                ":id" => $teacher_id,
                ":d"  => $desc
            ]);

            echo json_encode(["status" => "success", "state" => "presence", "teacher_id" => $teacher_id]);
            break;


        // ----------------------------
        //        انصراف
        // ----------------------------
        case "withdrawal":

            $desc = "withdrawal recorded by: $username";

            $stmt = $pdo->prepare("
                INSERT INTO attendance_teacher (teacher_id, state, date, description)
                VALUES (:id, 'withdrawal', NOW(), :d)
            ");

            $stmt->execute([
                ":id" => $teacher_id,
                ":d"  => $desc
            ]);

            echo json_encode(["status" => "success", "state" => "withdrawal", "teacher_id" => $teacher_id]);
            break;


        // ----------------------------
        //        غياب
        // ----------------------------
        case "absence":

            if (!$description) {
                echo json_encode(["status" => "error", "message" => "absence requires description"]);
                exit;
            }

            $desc = "absence: $description (added by $username)";

            $stmt = $pdo->prepare("
                INSERT INTO attendance_teacher (teacher_id, state, date, description)
                VALUES (:id, 'absence', NOW(), :d)
            ");

            $stmt->execute([
                ":id" => $teacher_id,
                ":d"  => $desc
            ]);

            echo json_encode(["status" => "success", "state" => "absence", "teacher_id" => $teacher_id]);
            break;


        default:
            echo json_encode(["status" => "error", "message" => "Invalid action"]);
    }

} catch (PDOException $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
