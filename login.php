<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include 'connectdb.php';
$json = file_get_contents('php://input');
$obj = json_decode($json, true);
$email = $obj['email'];
$password = $obj['password'];
$hashedPassword = md5($password);
$Sql_Query = "SELECT * FROM user WHERE email = '" . $email . "' AND password = '" . $hashedPassword . "'";
$allUsers = mysqli_query($dbcon, $Sql_Query);

if ($allUsers) {
    if (mysqli_num_rows($allUsers) > 0) {
        $all_users = mysqli_fetch_all($allUsers, MYSQLI_ASSOC);
        if (!empty($all_users)) {
            $userData = $all_users[0];
            $token = bin2hex(random_bytes(32));
            $userId = $userData['id'];

            // บันทึกการล็อกอินลงในตาราง user_login_history
            $currentDate = date('Y-m-d');
            $insertLoginHistoryQuery = "INSERT INTO user_login_history (user_id, login_date) VALUES ('$userId', '$currentDate')";
            mysqli_query($dbcon, $insertLoginHistoryQuery);

            if ($token) {
                $updatedUserData =
                    mysqli_fetch_assoc(mysqli_query($dbcon, "SELECT * FROM user WHERE id = '$userId'"));
                echo json_encode([
                    "success" => 1,
                    "token" => $token,
                    "user" => $updatedUserData
                ]);
            } else {
                echo json_encode(["success" => 0, "message" => "Token storage failed."]);
            }
        } else {
            echo json_encode(["success" => 0, "message" => "User data not found."]);
        }
    } else {
        echo json_encode(["success" => 0, "message" => "Invalid email or password."]);
    }
} else {
    echo json_encode(["success" => 0, "message" => "Database query failed."]);
}
?>
