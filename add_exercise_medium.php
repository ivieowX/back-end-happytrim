<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $name = $data['name'];
    $calorie = $data['calorie'];
    $exercise_image = $data['exercise_image'];
    $video_link = $data['video_link'];
    $details = $data['details'];
    $date = date('Y-m-d H:i:s'); // ปรับวันที่เป็นวันที่ปัจจุบัน

    $sql = "INSERT INTO exercise_medium (name, calorie, exercise_image, video_link, details, date) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($dbcon, $sql);
    mysqli_stmt_bind_param($stmt, "sissss", $name, $calorie, $exercise_image, $video_link, $details, $date);

    if (mysqli_stmt_execute($stmt)) {
        // เพิ่มการแจ้งเตือนลงในตาราง notifications
        $title = "กิจกรรมออกกำลังกายระดับกลางใหม่";
        $message = "มีการเพิ่มกิจกรรมออกกำลังกายระดับกลางใหม่: $name";
        $time = date('Y-m-d H:i:s');

        $notif_sql = "INSERT INTO notifications (title, message, time) VALUES (?, ?, ?)";
        $notif_stmt = mysqli_prepare($dbcon, $notif_sql);
        mysqli_stmt_bind_param($notif_stmt, "sss", $title, $message, $time);
        
        if (mysqli_stmt_execute($notif_stmt)) {
            echo json_encode(["success" => true, "message" => "Exercise added and notification sent successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Exercise added, but failed to send notification."]);
        }
        
        mysqli_stmt_close($notif_stmt);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to add exercise."]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

mysqli_close($dbcon);
?>
