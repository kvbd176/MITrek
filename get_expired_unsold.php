<?php
session_start();
include 'config.php';

if (!isset($_SESSION['store_name'])) {
    echo json_encode(["error" => "UNAUTHORIZED"]);
    exit();
}

$store = $_SESSION['store_name'];

$sql = "SELECT medicine_name, sl_no, batch_no, mfg_date, exp_date, quantity, cost 
        FROM medicines 
        WHERE store_name = ? 
          AND quantity > 0 
          AND exp_date < CURDATE()
        ORDER BY exp_date ASC";

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
