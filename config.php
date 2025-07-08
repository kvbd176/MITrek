<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "meditrack"; // You should create this in phpMyAdmin

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
