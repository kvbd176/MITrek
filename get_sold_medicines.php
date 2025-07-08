<?php
session_start();
include 'config.php';

if (!isset($_SESSION['store_name'])) {
    echo json_encode([]);
    exit();
}

$store = $_SESSION['store_name'];

$sql = "SELECT 
            sales.sold_at, 
            medicines.medicine_name AS med_name, 
            customers.name AS cust_name, 
            customers.phone, 
            sales.quantity
        FROM sales
        JOIN medicines ON sales.medicine_id = medicines.id
        JOIN customers ON sales.customer_id = customers.id
        WHERE medicines.store_name = ?
        ORDER BY sales.sold_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $store);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
