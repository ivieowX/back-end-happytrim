<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id'];
    $name = $data['name'];
    $calorie = $data['calorie'];
    $diet_image = $data['diet_image'];
    $details = $data['details'];

    $sql = "UPDATE diet_snack SET name=?, calorie=?, diet_image=?, details=? WHERE id=?";
    $stmt = mysqli_prepare($dbcon, $sql);
    mysqli_stmt_bind_param($stmt, "sissi", $name, $calorie, $diet_image, $details, $id);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => true, "message" => "Exercise updated successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update exercise."]);
    }

    mysqli_stmt_close($stmt);
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}

mysqli_close($dbcon);
?>
