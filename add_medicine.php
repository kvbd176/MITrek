<?php
session_start();
include 'config.php';

if (!isset($_SESSION['store_name'])) {
    echo "UNAUTHORIZED";
    exit();
}

$store_name = $_SESSION['store_name'];

$required_fields = [
    'sl_no', 'medicine_name', 'stock_entry_date',
    'mfg_date', 'exp_date', 'cost', 'batch_no',
    'distributor_name', 'medid', 'quantity'
];

foreach ($required_fields as $field) {
    if (empty($_POST[$field]) && $_POST[$field] !== "0") {
        echo "MISSING_FIELDS";
        exit();
    }
}

$sl_no = $_POST['sl_no'];
$medicine_name = $_POST['medicine_name'];
$stock_entry_date = $_POST['stock_entry_date'];
$mfg_date = $_POST['mfg_date'];
$exp_date = $_POST['exp_date'];
$cost = $_POST['cost'];
$batch_no = $_POST['batch_no'];
$distributor_name = $_POST['distributor_name'];
$medid = $_POST['medid'];
$quantity = $_POST['quantity'];
$sold = 0;

$sql = "INSERT INTO medicines (
    store_name, medicine_name, sl_no, stock_entry_date,
    mfg_date, exp_date, cost, batch_no, distributor_name,
    sold, quantity, medid
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "ssisssdssiii",  // 13 parameters: s=string, i=int, d=decimal
    $store_name,
    $medicine_name,
    $sl_no,
    $stock_entry_date,
    $mfg_date,
    $exp_date,
    $cost,
    $batch_no,
    $distributor_name,
    $sold,
    $quantity,
    $medid
);

if ($stmt->execute()) {
    echo "MEDICINE_ADDED";
} else {
    echo "ERROR: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
