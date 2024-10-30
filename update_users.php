<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include 'connectdb.php';

$json = file_get_contents('php://input');
$obj = json_decode($json, true);

$id = $obj['id'];
$name = $obj['name'];
$email = $obj['email'];
$password = isset($obj['password']) ? $obj['password'] : null; // รับรหัสผ่านใหม่ถ้ามี

// Check if the user with the given ID exists
$checkUserQuery = "SELECT * FROM user WHERE id = '$id'";
$checkUserResult = mysqli_query($dbcon, $checkUserQuery);

if (mysqli_num_rows($checkUserResult) > 0) {
    // Prepare the update query
    $updateFields = [];
    $updateValues = [];

    if (!empty($name)) {
        $updateFields[] = "name = ?";
        $updateValues[] = $name;
    }

    if (!empty($email)) {
        $updateFields[] = "email = ?";
        $updateValues[] = $email;
    }

    if (!empty($password)) {
        $hashedPassword = md5($password); // แฮชรหัสผ่าน
        $updateFields[] = "password = ?";
        $updateValues[] = $hashedPassword;
    }

    // สร้างคำสั่ง SQL ที่จะอัปเดต
    if (count($updateFields) > 0) {
        $updateQuery = "UPDATE user SET " . implode(", ", $updateFields) . " WHERE id = ?";
        $stmt = $dbcon->prepare($updateQuery);

        // Bind parameters dynamically
        $types = str_repeat('s', count($updateValues)) . 'i'; // สร้างชนิดข้อมูลสำหรับ bind
        $updateValues[] = $id; // เพิ่ม ID เป็นค่าที่ต้องอัปเดต
        $stmt->bind_param($types, ...$updateValues);

        if ($stmt->execute()) {
            echo json_encode(["success" => 1, "message" => "User information updated successfully."]);
        } else {
            echo json_encode(["success" => 0, "message" => "Failed to update user information."]);
        }

        $stmt->close();
    } else {
        echo json_encode(["success" => 0, "message" => "No fields to update."]);
    }
} else {
    echo json_encode(["success" => 0, "message" => "User not found."]);
}

$dbcon->close();
?>
