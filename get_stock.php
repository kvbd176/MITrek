<?php
session_start();
include 'config.php';

if (!isset($_SESSION['store_name'])) {
    echo json_encode(["error" => "UNAUTHORIZED"]);
    exit();
}

$store = $_SESSION['store_name'];

$sql = "SELECT medid, medicine_name, SUM(quantity) AS total_quantity, 
               SUM(sold) AS total_sold, cost, batch_no, mfg_date, exp_date, distributor_name
        FROM medicines 
        WHERE store_name = ? AND quantity > 0
        GROUP BY medid, medicine_name, cost, batch_no, mfg_date, exp_date, distributor_name
        ORDER BY medicine_name";

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
