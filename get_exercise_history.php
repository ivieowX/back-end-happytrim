<?php
header("Content-Type: application/json");
include 'connectdb.php'; // ใช้ไฟล์เชื่อมต่อฐานข้อมูลของคุณ

$response = array('success' => false);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // ตรวจสอบว่ามีการส่ง user_id มาหรือไม่
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];

        // สร้าง SQL สำหรับดึงข้อมูลประวัติการออกกำลังกายของผู้ใช้
        $sql = "SELECT name, calorie, date FROM exercises_history WHERE user_id = ?";
        $stmt = $dbcon->prepare($sql);
        $stmt->bind_param('i', $user_id);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $history = array();

            while ($row = $result->fetch_assoc()) {
                $history[] = $row;
            }

            $response['success'] = true;
            $response['data'] = $history;
        } else {
            $response['message'] = "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $response['message'] = "Missing user_id.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
$dbcon->close();
?>
