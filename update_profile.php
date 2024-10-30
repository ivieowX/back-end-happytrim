<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'connectdb.php';

// Get raw POST data
$json = file_get_contents('php://input');

// Check if JSON is received
if (!$json) {
    echo json_encode(["success" => 0, "message" => "No input received."]);
    exit();
}

// Decode JSON input
$obj = json_decode($json, true);

// Check if JSON decoding was successful
if (!$obj) {
    echo json_encode(["success" => 0, "message" => "Invalid JSON data."]);
    exit();
}

// Check if required fields are present in the decoded JSON
if (!isset($obj['id']) || !isset($obj['name']) || !isset($obj['email']) || !isset($obj['age'])) {
    echo json_encode(["success" => 0, "message" => "Missing required fields."]);
    exit();
}

$id = $obj['id'];
$name = $obj['name'];
$email = $obj['email'];
$age = $obj['age'];

$id = mysqli_real_escape_string($dbcon, $id);
$name = mysqli_real_escape_string($dbcon, $name);
$email = mysqli_real_escape_string($dbcon, $email);
$age = mysqli_real_escape_string($dbcon, $age);

$checkUserQuery = "SELECT * FROM user WHERE id = '$id'";
$checkUserResult = mysqli_query($dbcon, $checkUserQuery);

if (mysqli_num_rows($checkUserResult) > 0) {
    $updateUserQuery = "UPDATE user SET name = ?, email = ?, age = ? WHERE id = ?";
    $stmt = $dbcon->prepare($updateUserQuery);
    $stmt->bind_param("ssii", $name, $email, $age, $id);  // Bind the parameters

    if ($stmt->execute()) {
        echo json_encode(["success" => 1, "message" => "User information updated successfully."]);
    } else {
        echo json_encode(["success" => 0, "message" => "Failed to update user information."]);
    }
} else {
    echo json_encode(["success" => 0, "message" => "User not found."]);
}

$dbcon->close();
?>
