<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database configuration
$serverName = "localhost";
$userName = "6460704003";
$userPassword = "6460704003";
$dbName = "6460704003";

// Create a connection
$dbcon = mysqli_connect($serverName, $userName, $userPassword, $dbName);

// Set character set to UTF-8
mysqli_set_charset($dbcon, "utf8");

// Check the connection
if (mysqli_connect_errno()) {
    echo "Database Connection Failed: " . mysqli_connect_error();
    exit();
} else {
    // echo "Database Connected.";
}
?>
