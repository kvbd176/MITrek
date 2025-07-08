<?php
session_start();
include 'config.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Use password_verify to compare the entered password with hashed password
    if (password_verify($password, $row['password'])) {
        $_SESSION['email'] = $row['email'];
        $_SESSION['store_name'] = $row['store_name'];
        echo "SUCCESS";
    } else {
        echo "WRONG_PASSWORD";
    }
} else {
    echo "NOT_FOUND";
}
?>
