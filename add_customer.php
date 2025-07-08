<?php
session_start();
include 'config.php';

if (!isset($_SESSION['store_name'])) {
    echo "UNAUTHORIZED";
    exit();
}

$store_name = $_SESSION['store_name'];
$name = $_POST['name'];
$phone = $_POST['phone'];
$address = $_POST['address'];

// Check if customer already exists in the same store
$check = $conn->prepare("SELECT id FROM customers WHERE phone = ? AND store_name = ?");
$check->bind_param("ss", $phone, $store_name);
$check->execute();
$checkResult = $check->get_result();

if ($checkResult->num_rows === 0) {
    $stmt = $conn->prepare("INSERT INTO customers (name, phone, address, store_name) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $phone, $address, $store_name);

    if ($stmt->execute()) {
        echo "CUSTOMER_ADDED";
    } else {
        echo "ERROR";
    }

    $stmt->close();
} else {
    echo "EXISTS";
}

$check->close();
$conn->close();
?>
