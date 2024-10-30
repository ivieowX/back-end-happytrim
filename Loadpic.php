<?php
include 'connectdb.php';
$Sql = "SELECT * FROM payment";
$Query = mysqli_query($dbcon, $Sql);
while ($row = mysqli_fetch_assoc($Query)) {
    $json_array[] = $row;
}
echo json_encode($json_array);
mysqli_close($dbcon);
