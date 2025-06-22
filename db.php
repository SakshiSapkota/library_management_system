<?php
$host = "127.0.0.1:3307";
$user = "root";
$pass = "";
$dbname = "library_system";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
