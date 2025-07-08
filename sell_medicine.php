<?php
session_start();
include 'config.php';

if (!isset($_SESSION['store_name'])) {
    echo "UNAUTHORIZED";
    exit();
}

$store_name = $_SESSION['store_name'];
$customer_id = $_POST['customer_id'];
$medid = $_POST['medicine_id'];
$required_qty = (int) $_POST['quantity'];

// Get available medicines for the medid under current store
$sql = "SELECT id, quantity, sold FROM medicines 
        WHERE store_name = ? AND medid = ? AND quantity > 0 
        ORDER BY stock_entry_date ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $store_name, $medid);
$stmt->execute();
$result = $stmt->get_result();

$total_available = 0;
$medicines = [];
while ($row = $result->fetch_assoc()) {
    $medicines[] = $row;
    $total_available += $row['quantity'];
}

if ($total_available < $required_qty) {
    echo "NOT_ENOUGH_STOCK";
    exit();
}

// Now sell the medicine from available entries
$left_to_sell = $required_qty;

foreach ($medicines as $med) {
    if ($left_to_sell <= 0) break;

    $take = min($med['quantity'], $left_to_sell);
    $new_qty = $med['quantity'] - $take;
    $new_sold = $med['sold'] + $take;

    // Update medicines table
    $update = $conn->prepare("UPDATE medicines SET quantity = ?, sold = ? WHERE id = ?");
    $update->bind_param("iii", $new_qty, $new_sold, $med['id']);
    $update->execute();

    // Insert into sales
    $insert = $conn->prepare("INSERT INTO sales (customer_id, medicine_id, quantity) VALUES (?, ?, ?)");
    $insert->bind_param("iii", $customer_id, $med['id'], $take);
    $insert->execute();

    $left_to_sell -= $take;
}

echo "SOLD_SUCCESS";
?>
