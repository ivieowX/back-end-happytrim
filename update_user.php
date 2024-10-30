<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'connectdb.php';

$json = file_get_contents('php://input');

if (!$json) {
    echo json_encode(["success" => 0, "message" => "No input received."]);
    exit();
}

$obj = json_decode($json, true);

if (!$obj) {
    echo json_encode(["success" => 0, "message" => "Invalid JSON object."]);
    exit();
}

if (!isset($obj['id']) || !isset($obj['email']) || !isset($obj['weight']) || !isset($obj['height']) || !isset($obj['weight_goal'])) {
    echo json_encode(["success" => 0, "message" => "Missing required fields."]);
    exit();
}

$id = mysqli_real_escape_string($dbcon, $obj['id']);
$email = mysqli_real_escape_string($dbcon, $obj['email']);
$weight = mysqli_real_escape_string($dbcon, $obj['weight']);
$height = mysqli_real_escape_string($dbcon, $obj['height']);
$weight_goal = mysqli_real_escape_string($dbcon, $obj['weight_goal']);


$checkUserQuery = "SELECT * FROM user WHERE id = '$id'";
$checkUserResult = mysqli_query($dbcon, $checkUserQuery);

if (mysqli_num_rows($checkUserResult) > 0) {
    $updateUserQuery = "UPDATE user SET email = ?, weight = ?, height = ?, weight_goal = ? WHERE id = ?";
    $stmt = $dbcon->prepare($updateUserQuery);
    $stmt->bind_param("ssssi", $email, $weight, $height, $weight_goal, $id); 

    if ($stmt->execute()) {
        echo json_encode(['success' => 1, 'message' => 'ข้อมูลถูกอัพเดทเรียบร้อยแล้ว']);
    } else {
        echo json_encode(['success' => 0, 'message' => 'เกิดข้อผิดพลาดในการอัพเดทข้อมูล']);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => 0, "message" => "User not found."]);
}

$dbcon->close();
?>
