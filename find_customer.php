<?php
session_start();
include 'config.php';

if (!isset($_SESSION['store_name'])) {
    echo json_encode(["error" => "UNAUTHORIZED"]);
    exit();
}

if (!isset($_POST['phone'])) {
    echo json_encode(["error" => "Missing phone"]);
    exit();
}

$store_name = $_SESSION['store_name'];
$phone = $_POST['phone'];
$response = [];

// Step 1: Get customer by phone and store
$sql = "SELECT * FROM customers WHERE phone = ? AND store_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $phone, $store_name);
$stmt->execute();
$customerResult = $stmt->get_result();

if ($customer = $customerResult->fetch_assoc()) {
    $customer_id = $customer['id'];
    $response['customer'] = $customer;

    // Step 2: Get purchases only from this store
    $purchaseSql = "SELECT m.medicine_name, s.quantity, s.sold_at 
                    FROM sales s 
                    JOIN medicines m ON s.medicine_id = m.id 
                    WHERE s.customer_id = ? AND m.store_name = ?";
    $purchaseStmt = $conn->prepare($purchaseSql);
    $purchaseStmt->bind_param("is", $customer_id, $store_name);
    $purchaseStmt->execute();
    $purchaseResult = $purchaseStmt->get_result();

    $purchases = [];
    while ($row = $purchaseResult->fetch_assoc()) {
        $purchases[] = $row;
    }

    $response['purchases'] = $purchases;
    echo json_encode($response);
} else {
    echo json_encode(["error" => "NOT_FOUND"]);
}
?>
