<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'connectdb.php'; // Include your database connection file

$json = file_get_contents('php://input');
$obj = json_decode($json, true);

$oldPassword = $obj['password'];
$newPassword = $obj['new_password'];
$userId = $obj['id']; // Assuming you are sending user ID along with the request

// Hash the old password for comparison
$hashedOldPassword = md5($oldPassword);

// Query to check if the old password is correct
$checkPasswordQuery = "SELECT * FROM user WHERE id = '$userId' AND password = '$hashedOldPassword'";
$result = mysqli_query($dbcon, $checkPasswordQuery);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        // Old password is correct, update the new password
        $hashedNewPassword = md5($newPassword); // Hash the new password
        $updatePasswordQuery = "UPDATE user SET password = '$hashedNewPassword' WHERE id = '$userId'";
        
        if (mysqli_query($dbcon, $updatePasswordQuery)) {
            echo json_encode(["success" => 1, "message" => "Password changed successfully."]);
        } else {
            echo json_encode(["success" => 0, "message" => "Failed to update password."]);
        }
    } else {
        echo json_encode(["success" => 0, "message" => "Old password is incorrect."]);
    }
} else {
    echo json_encode(["success" => 0, "message" => "Database query failed."]);
}

// Close the database connection
mysqli_close($dbcon);
?>
