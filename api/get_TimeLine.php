<?php
// api.php
require_once '../hmb/conn.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? '';

try {
    switch($action) {
        case 'get_teachers':
            $stmt = $pdo->query("SELECT * FROM teachers");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'get_subjects':
            $stmt = $pdo->query("SELECT * FROM subjects");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'get_classes':
            $stmt = $pdo->query("SELECT * FROM classes");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'get_slots':
            $stmt = $pdo->query("SELECT * FROM class_slots");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'get_timetable':
            $class_id = $_GET['class_id'] ?? '';
            $week_start = $_GET['week_start'] ?? date('Y-m-d');
            
            $sql = "SELECT t.*, s.subject_name, s.color, tc.name as teacher_name, 
                           c.class_name, cs.start_time, cs.end_time
                    FROM timetable t
                    JOIN subjects s ON t.subject_id = s.subject_id
                    JOIN teachers tc ON t.teacher_id = tc.teacher_id
                    JOIN classes c ON t.class_id = c.class_id
                    JOIN class_slots cs ON t.slot_id = cs.slot_id
                    WHERE t.effective_date >= ? AND t.effective_date < DATE_ADD(?, INTERVAL 7 DAY)";
            
            $params = [$week_start, $week_start];
            
            if ($class_id) {
                $sql .= " AND t.class_id = ?";
                $params[] = $class_id;
            }
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'get_today_classes':
            $class_id = $_GET['class_id'] ?? '';
            $today = date('Y-m-d');
            $day_of_week = strtolower(date('l'));
            
            $sql = "SELECT t.*, s.subject_name, s.color, tc.name as teacher_name, 
                           c.class_name, cs.start_time, cs.end_time
                    FROM timetable t
                    JOIN subjects s ON t.subject_id = s.subject_id
                    JOIN teachers tc ON t.teacher_id = tc.teacher_id
                    JOIN classes c ON t.class_id = c.class_id
                    JOIN class_slots cs ON t.slot_id = cs.slot_id
                    WHERE t.day_of_week = ? AND t.effective_date <= ? 
                    AND (t.academic_year = '2023-2024' OR t.academic_year IS NULL)";
            
            $params = [$day_of_week, $today];
            
            if ($class_id) {
                $sql .= " AND t.class_id = ?";
                $params[] = $class_id;
            }
            
            $sql .= " ORDER BY cs.start_time";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'add_timetable_entry':
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sql = "INSERT INTO timetable (class_id, subject_id, teacher_id, slot_id, day_of_week, effective_date, academic_year) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                $data['class_id'],
                $data['subject_id'],
                $data['teacher_id'],
                $data['slot_id'],
                $data['day_of_week'],
                $data['effective_date'],
                $data['academic_year']
            ]);
            
            echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
            break;

        default:
            echo json_encode(['error' => 'Invalid action']);
    }
} catch(PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>