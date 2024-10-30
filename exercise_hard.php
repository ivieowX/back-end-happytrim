<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'connectdb.php';

$sql = "SELECT * FROM exercise_hard";
$result = mysqli_query($dbcon, $sql);

if ($result) {
    $exercise_hard = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $exercise_hard[] = $row;
    }
    echo json_encode(["success" => true, "exercise_hard" => $exercise_hard]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to fetch exercise easy."]);
}

mysqli_close($dbcon);
