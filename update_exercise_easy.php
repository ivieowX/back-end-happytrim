<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
include 'connectdb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $id = $data['id'];
    $name = $data['name'];
    $calorie = $data['calorie'];
    $exercise_image = $data['exercise_image'];
    $video_link = $data['video_link'];
    $details = $data['details'];

    $sql = "UPDATE exercise_easy SET name=?, calorie=?, exercise_image=?, video_link=?, details=? WHERE id=?";
    $stmt = mysqli_prepare($dbcon, $sql);
    mysqli_stmt_bind_param($stmt, "sisssi", $name, $calorie, $exercise_image, $video_link, $details, $id);

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
