<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'connectdb.php';

$response = array('success' => false);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    $user_id = $input['user_id'] ?? null;
    $name = $input['name'] ?? null;
    $calorie = $input['calorie'] ?? null;
    $date = date('Y-m-d H:i:s'); // ปรับวันที่เป็นวันที่ปัจจุบัน

    if ($user_id && $name && $calorie) {
        // เตรียม SQL statement สำหรับการบันทึกข้อมูล
        $sql = "INSERT INTO exercises_history (user_id, name, calorie, date) VALUES (?, ?, ?, ?)";
        $stmt = $dbcon->prepare($sql);
        $stmt->bind_param('isis', $user_id, $name, $calorie, $date);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Exercise history saved successfully.";
        } else {
            $response['message'] = "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = "Missing required fields: user_id, name, or calorie.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
$dbcon->close();
