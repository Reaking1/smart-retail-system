<?php
$host = "localhost";
$user = "root";    // default in XAMPP
$pass = "";        // leave blank if no password set
$db   = "smart_retail";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
