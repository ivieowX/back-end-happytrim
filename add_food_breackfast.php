<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $name = $data['name'];
    $calorie = $data['calorie'];
    $diet_image = $data['diet_image'];
    $details = $data['details'];
    $date = date('Y-m-d H:i:s'); // ปรับวันที่เป็นวันที่ปัจจุบัน

    $sql = "INSERT INTO diet_breakfast (name, calorie, diet_image, details, date) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($dbcon, $sql);
    mysqli_stmt_bind_param($stmt, "sisss", $name, $calorie, $diet_image, $details, $date);

    if (mysqli_stmt_execute($stmt)) {
        // เพิ่มการแจ้งเตือนเมื่อเพิ่มอาหารเช้าใหม่
        $notification_title = "มีอาหารเช้าใหม่";
        $notification_message = "อาหารเช้าใหม่: " . $name;
        $notification_time = date('Y-m-d H:i:s'); // เวลาปัจจุบันสำหรับการแจ้งเตือน

        $sql_notification = "INSERT INTO notifications (title, message, time) VALUES (?, ?, ?)";
        $stmt_notification = mysqli_prepare($dbcon, $sql_notification);
        mysqli_stmt_bind_param($stmt_notification, "sss", $notification_title, $notification_message, $notification_time);

        if (!mysqli_stmt_execute($stmt_notification)) {
            // หากเกิดข้อผิดพลาดในการเพิ่มการแจ้งเตือน สามารถบันทึกไว้ใน log หรือจัดการได้
        }

        mysqli_stmt_close($stmt_notification); // ปิด statement สำหรับการแจ้งเตือน

        echo json_encode(["success" => true, "message" => "Food added successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add food."]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

mysqli_close($dbcon);
?>
