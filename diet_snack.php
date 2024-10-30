<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'connectdb.php';

$sql = "SELECT * FROM diet_snack";
$result = mysqli_query($dbcon, $sql);

if ($result) {
    $diet_snack = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $diet_snack[] = $row;
    }
    echo json_encode(["success" => true, "diet_snack" => $diet_snack]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to fetch exercise easy."]);
}

mysqli_close($dbcon);
