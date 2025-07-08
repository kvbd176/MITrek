<?php
include 'config.php'; // assumes $conn is your DB connection

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "INVALID_REQUEST";
    exit();
}

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    echo "All fields are required.";
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo "Invalid email address.";
    exit();
}

$stmt = $conn->prepare("INSERT INTO contact(name, email, message) VALUES (?, ?, ?)");
if (!$stmt) {
    echo "DB_ERROR_PREPARE";
    exit();
}

$stmt->bind_param("sss", $name, $email, $message);
if ($stmt->execute()) {
    echo "SUCCESS";
} else {
    echo "DB_ERROR_EXECUTE";
}

$stmt->close();
$conn->close();
?>
