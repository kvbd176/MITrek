<?php
session_start();
include 'config.php';

if (!isset($_SESSION['store_name'])) {
    echo json_encode([]);
    exit();
}

$store = $_SESSION['store_name'];

$sql = "SELECT sl_no, medicine_name, exp_date, batch_no, cost
        FROM medicines
        WHERE store_name = ?
          AND quantity > 0
          AND exp_date > CURDATE()
          AND exp_date <= DATE_ADD(CURDATE(), INTERVAL 2 MONTH)";

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
