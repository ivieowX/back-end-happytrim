<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'connectdb.php';

$sql = "SELECT * FROM exercise_medium";
$result = mysqli_query($dbcon, $sql);

if ($result) {
    $exercise_medium = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $exercise_medium[] = $row;
    }
    echo json_encode(["success" => true, "exercise_medium" => $exercise_medium]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to fetch exercise plans."]);
}

mysqli_close($dbcon);
