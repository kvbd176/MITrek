<?php
$host = "sql311.infinityfree.com";
$user = "if0_39424368";
$password = "Srajee2005";
$database = "if0_39424368_MITrek"; // You should create this in phpMyAdmin

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
