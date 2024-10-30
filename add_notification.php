<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'connectdb.php';

// รับข้อมูล JSON จากการร้องขอ
$json = file_get_contents('php://input');

if (!$json) {
    echo json_encode(["success" => false, "message" => "No input received."]);
    exit();
}

$obj = json_decode($json, true);

if (!$obj || !isset($obj['title']) || !isset($obj['message'])) {
    echo json_encode(["success" => false, "message" => "Invalid input data."]);
    exit();
}

// ดึงข้อมูลจาก JSON
$title = mysqli_real_escape_string($dbcon, $obj['title']);
$message = mysqli_real_escape_string($dbcon, $obj['message']);
$time = date('Y-m-d H:i:s'); // เวลาในการเพิ่มการแจ้งเตือน

// สร้างคำสั่ง SQL สำหรับเพิ่มการแจ้งเตือน
$sql = "INSERT INTO notifications (title, message, time) VALUES (?, ?, ?)";
$stmt = $dbcon->prepare($sql);
$stmt->bind_param("sss", $title, $message, $time);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Notification added successfully.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding notification.']);
}

$stmt->close();
$dbcon->close();
?>
