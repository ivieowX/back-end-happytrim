<?php
include 'connectdb.php';

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$dbcon) {
    die(json_encode(["error" => "Failed to connect to the database"]));
}

$email = $_GET['email'];

// ใช้ prepared statement เพื่อป้องกัน SQL Injection
$stmt = $dbcon->prepare("SELECT * FROM user WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// ตรวจสอบว่ามีผลลัพธ์หรือไม่
if ($result->num_rows > 0) {
    $sqlPro = $result->fetch_assoc();
    echo json_encode($sqlPro);
} else {
    echo json_encode(["error" => "User not found"]);
}

// ปิดการเชื่อมต่อฐานข้อมูลและ statement
$stmt->close();
mysqli_close($dbcon);
?>
