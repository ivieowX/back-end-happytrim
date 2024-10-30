<?php
include('connectdb.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $userId = $data['userId'];
    $bmi = $data['bmi'];
    $date = $data['date'];

    if (isset($userId) && isset($bmi) && isset($date)) {
        $stmt = $dbcon->prepare("INSERT INTO user_bmi (user_id, bmi, date) VALUES (?, ?, ?)");
        $stmt->bind_param("ids", $userId, $bmi, $date);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error inserting BMI']);
        }

        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
    }
}

$dbcon->close();
?>
