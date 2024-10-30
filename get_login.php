<?php
include 'connectdb.php';

$email = $_POST['email'];
$password = $_POST['password'];

$query = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
$result = mysqli_query($dbcon, $query);
$user = mysqli_fetch_assoc($result);

if ($user) {
    $response['success'] = 1;
    $response['user'] = $user;
    $response['token'] = bin2hex(random_bytes(16));

    // เพิ่มข้อมูลล็อกอินในแต่ละวันลงในตาราง logins
    $date = date('Y-m-d');
    $logQuery = "INSERT INTO logins (user_id, login_date) VALUES ('{$user['id']}', '$date')";
    mysqli_query($dbcon, $logQuery);
} else {
    $response['success'] = 0;
    $response['message'] = 'Invalid login';
}

echo json_encode($response);
?>
