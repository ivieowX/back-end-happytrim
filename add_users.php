<?php
include 'connectdb.php';

// รับข้อมูล JSON จาก React Native
$json = file_get_contents('php://input');
$obj = json_decode($json, true);

// รับค่าจาก JSON
$name = $obj['name'];
$email = $obj['email'];
$password = $obj['password'];
$weight = $obj['weight'];
$height = $obj['height'];
$weight_goal = $obj['weight_goal'];
$gender = $obj['gender'];  // เพิ่ม gender
$age = $obj['age'];        // เพิ่ม age
$userstatus = "users";

// ตรวจสอบว่ามี email นี้ในระบบหรือไม่
$CheckSQL = "SELECT * FROM user WHERE email='$email'";
$check = mysqli_fetch_array(mysqli_query($dbcon, $CheckSQL));

if (isset($check)) {
    $EmailExistMSG = 'พบข้อมูลซ้ำ!';
    $EmailExistJson = json_encode($EmailExistMSG);
    echo $EmailExistJson;
} else {
    // แฮชรหัสผ่าน
    $hashedPassword = md5($password);

    // สร้าง token สำหรับผู้ใช้
    $token = bin2hex(random_bytes(32));

    // เพิ่มข้อมูลเข้าในฐานข้อมูล พร้อม weight, height, weight_goal, gender, age, token
    $Sql_Query = "INSERT INTO user (name, email, status, password, weight, height, weight_goal, gender, age, token) 
                  VALUES ('$name', '$email', '$userstatus', '$hashedPassword', '$weight', '$height', '$weight_goal', '$gender', '$age', '$token')";

    if ($dbcon->query($Sql_Query) === TRUE) {
        $MSG = 'สมัครสมาชิกสำเร็จ';
        $json = json_encode([
            "message" => $MSG,
            "token" => $token
        ]);
        echo $json;
    } else {
        $ErrorMsg = 'เกิดข้อผิดพลาด';
        $json = json_encode(["message" => $ErrorMsg]);
        echo $json;
    }
}

$dbcon->close();
?>
