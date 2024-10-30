<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // ปรับตามต้องการในเรื่องความปลอดภัย

include 'connectdb.php';

// ตรวจสอบว่าได้ส่ง user_id มาหรือไม่
$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;

if ($user_id > 0) {
    // ดึงข้อมูลการแจ้งเตือนทั้งหมดของผู้ใช้ที่ระบุ
    $sql = "SELECT id, title, message, time, isRead FROM notificat_user_regis WHERE user_id = ? ORDER BY time DESC";
    
    $stmt = $dbcon->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $notifications = [];
    $unreadCount = 0;

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $notifications[] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'message' => $row['message'],
                'time' => $row['time'],
                'isRead' => $row['isRead']
            ];
            
            // นับจำนวนการแจ้งเตือนที่ยังไม่ได้อ่าน (isRead = 0)
            if ($row['isRead'] == 0) {
                $unreadCount++;
            }
        }
        echo json_encode([
            'success' => true, 
            'notifications' => $notifications, 
            'unreadCount' => $unreadCount
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No notifications found for this user']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
}

$dbcon->close();
?>
