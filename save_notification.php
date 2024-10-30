<?php
// เชื่อมต่อกับฐานข้อมูล
include('connectdb.php');

header('Content-Type: application/json');

// รับข้อมูลจากคำขอ POST
$data = json_decode(file_get_contents("php://input"), true);

$title = isset($data['title']) ? $data['title'] : '';
$message = isset($data['message']) ? $data['message'] : '';
$time = isset($data['time']) ? $data['time'] : '';

// ตรวจสอบว่าข้อมูลครบถ้วน
if (empty($title) || empty($message) || empty($time)) {
    echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบ']);
    exit;
}

// เตรียมคำสั่ง SQL
$query = "INSERT INTO notifications (title, message, time) VALUES (?, ?, ?)";
$stmt = $dbcon->prepare($query);
$stmt->bind_param("sss", $title, $message, $time);

// ดำเนินการคำสั่ง
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'บันทึกการแจ้งเตือนสำเร็จ']);
} else {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการบันทึกการแจ้งเตือน']);
}

// ปิดการเชื่อมต่อ
$stmt->close();
$dbcon->close();
?>
