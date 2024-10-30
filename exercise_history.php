<?php
include 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_POST['user_id']; // รับ userId จากการเรียก API
    $exerciseName = $_POST['exercise_name'];
    $date = date("Y-m-d H:i:s"); // เวลาปัจจุบัน

    // บันทึกข้อมูลลงฐานข้อมูล
    $stmt = $dbcon->prepare("INSERT INTO exercise_history (user_id, exercise_name, date) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $userId, $exerciseName, $date);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
    $dbcon->close();
}
?>