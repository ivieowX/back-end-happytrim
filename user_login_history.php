<?php
include 'connectdb.php';

$query = "SELECT login_date, COUNT(*) as login_count FROM user_login_history GROUP BY login_date";
$result = mysqli_query($dbcon, $query);

$response = [];
if ($result) {
    $loginData = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $loginData[] = [
            'date' => $row['login_date'],
            'count' => (int)$row['login_count']
        ];
    }
    $response['success'] = 1;
    $response['loginData'] = $loginData;
} else {
    $response['success'] = 0;
    $response['message'] = 'Failed to fetch login data';
}

echo json_encode($response);
?>
