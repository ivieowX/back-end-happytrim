<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include 'connectdb.php';

// ตรวจสอบว่ามีการส่ง ID ผู้ใช้หรือไม่
if (!isset($_GET['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}

$userId = intval($_GET['user_id']); // ตรวจสอบให้แน่ใจว่า ID เป็นตัวเลข

$sql = "SELECT id, title, message, time FROM notifications WHERE user_id = $userId ORDER BY time DESC";
$result = $dbcon->query($sql);

$newUserNotifications = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $newUserNotifications[] = [
            'id' => $row['id'],
            'title' => $row['title'],
            'message' => $row['message'],
            'time' => $row['time'],
        ];
    }
    echo json_encode(['success' => true, 'notifications' => $newUserNotifications]);
} else {
    echo json_encode(['success' => false, 'message' => 'No new user notifications found']);
}

$dbcon->close();
?>
