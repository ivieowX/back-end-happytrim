<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'connectdb.php';

$sql = "SELECT * FROM diet_dinner";
$result = mysqli_query($dbcon, $sql);

if ($result) {
    $diet_dinner = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $diet_dinner[] = $row;
    }
    echo json_encode(["success" => true, "diet_dinner" => $diet_dinner]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to fetch exercise easy."]);
}

mysqli_close($dbcon);
