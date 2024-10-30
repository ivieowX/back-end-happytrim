<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: DELETE");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include 'connectdb.php';
if (isset($_GET['id'])) {
    $mediumId = $_GET['id'];
    $deleteUserQuery = "DELETE FROM exercise_medium WHERE id = $mediumId";
    if (mysqli_query($dbcon, $deleteUserQuery)) {
        echo json_encode(["success" => 1, "message" => "User deleted successfully."]);
    } else {
        echo json_encode(["success" => 0, "message" => "Failed to delete user."]);
    }
} else {
    echo json_encode(["success" => 0, "message" => "Missing 'id' parameter."]);
}
