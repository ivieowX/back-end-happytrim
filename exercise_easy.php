<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $easy_id = $data['id']; // รับ ID ของการออกกำลังกายที่จะลบ

    // ตรวจสอบว่ามีการส่ง ID มาหรือไม่
    if (!isset($easy_id)) {
        echo json_encode(["success" => false, "message" => "Missing exercise ID."]);
        exit;
    }

    // สร้างคำสั่ง SQL เพื่อทำการลบ
    $sql = "DELETE FROM exercise_easy WHERE id = ?";
    $stmt = mysqli_prepare($dbcon, $sql);
    mysqli_stmt_bind_param($stmt, "i", $easy_id); // ใช้ "i" เพราะ ID เป็นจำนวนเต็ม

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => true, "message" => "Exercise deleted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to delete exercise."]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

mysqli_close($dbcon);
?>
