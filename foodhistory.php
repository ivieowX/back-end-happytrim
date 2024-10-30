<?php
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include 'connectdb.php';

if ($dbcon->connect_error) {
    die(json_encode(["success" => false, "message" => "Connection failed"]));
}

// ตรวจสอบว่าเป็นการร้องขอเพื่อบันทึกข้อมูลอาหารหรือดึงข้อมูลประวัติอาหาร
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestMethod === 'POST') {
    // สำหรับการบันทึกอาหาร
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    $user_id = $data['user_id'];
    $food_id = $data['food_id'];
    $date = date('Y-m-d');

    $query = "INSERT INTO food_history (user_id, food_id, date) VALUES ('$user_id', '$food_id', '$date')";
    $result = $dbcon->query($query);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Food saved successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to save food"]);
    }
} elseif ($requestMethod === 'GET') {
    // สำหรับการดึงประวัติอาหาร
    $user_id = $_GET['user_id']; // ดึง user_id จาก query parameter

    $query = "SELECT food_history.food_id, food.name, food.calorie FROM food_history 
              JOIN food ON food_history.food_id = food.id 
              WHERE food_history.user_id = '$user_id'";
    
    $result = $dbcon->query($query);

    if ($result && $result->num_rows > 0) {
        $history = [];
        while ($row = $result->fetch_assoc()) {
            $history[] = [
                'id' => $row['food_id'],
                'name' => $row['name'],
                'calorie' => $row['calorie']
            ];
        }
        echo json_encode(["success" => true, "history" => $history]);
    } else {
        echo json_encode(["success" => false, "message" => "No food history found"]);
    }
}

$dbcon->close();
