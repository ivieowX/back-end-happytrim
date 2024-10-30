<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'connectdb.php';
$sql = "SELECT * FROM user";
$result = mysqli_query($dbcon, $sql);
if ($result) {
    $users = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $users[] = $row;
    }
    echo json_encode(["success" => true, "users" => $users]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to fetch users."]);
}
mysqli_close($dbcon);
