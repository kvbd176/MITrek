<?php
include 'config.php';

$sql = "SELECT sales.*, customers.name AS customer_name, customers.phone, medicines.name AS medicine_name
        FROM sales
        JOIN customers ON sales.customer_id = customers.id
        JOIN medicines ON sales.medicine_id = medicines.id
        ORDER BY sales.sold_at DESC";

$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
echo json_encode($data);
?>
