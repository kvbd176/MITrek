<?php
include 'config.php';

// Get user inputs
$username = $_POST['username'];
$email = $_POST['email'];
$store_name = $_POST['store'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Check if username already exists
$checkStmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$checkStmt->bind_param("s", $username);
$checkStmt->execute();
$checkResult = $checkStmt->get_result();

if ($checkResult->num_rows > 0) {
    echo "USERNAME_EXISTS";
} else {
    // Insert new user
    $insertStmt = $conn->prepare("INSERT INTO users (username, email, store_name, password) VALUES (?, ?, ?, ?)");
    $insertStmt->bind_param("ssss", $username, $email, $store_name, $password);
    
    if ($insertStmt->execute()) {
        echo "REGISTERED";
    } else {
        echo "ERROR";
    }

    $insertStmt->close();
}

$checkStmt->close();
$conn->close();
?>
