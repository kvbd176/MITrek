<?php
session_start();
if (!isset($_SESSION['email'])) {
  header("Location: login.php");
  exit();
}
$store_name = $_SESSION['store_name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Medicine Store Dashboard</title>
  <link rel="icon" href="meditrack_logo.jpg"/>
  <!-- <link rel="stylesheet" href="mpage.css" /> -->
   <link rel="stylesheet" href="mpage.css?v=<?php echo time(); ?>" />


  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>

  <!-- Navbar -->
  <div class="navbar">
    <div class="nav-logo">
      <div class="logo border"></div>
      <p class="heading">MITrek</p>
    </div>
    <div class="profile-menu">
      <i class='bx bxs-user profile-icon' onclick="toggleDropdown()"></i>
      <div id="dropdown" class="dropdown hidden">
        <p onclick="window.location.href='logout.php'">Logout</p>
      </div>
    </div>
  </div>

  <!-- Main Layout -->
  <div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
      <h2>Dashboard</h2>
      <button onclick="showSection('dashboard')">Home</button>
      <button onclick="showSection('addMedicine')">Add Medicine</button>
      <button onclick="showSection('expiringStock')">About to Expire</button>
      <button onclick="showSection('soldStock')">Sold Medicines</button>
      <button onclick="showSection('findCustomer')">Find Customer</button>
      <button onclick="showSection('addCustomer')">Add Customer</button>
      <button onclick="showSection('presentStock')">Present Stock</button>
      <button onclick="showSection('expiredmed')">Expired Medicines</button>
    </div>

    <!-- Main Content -->
    <div class="main">

      <!-- Home -->
      <div id="dashboard" class="section active">
        <p id="dashboardGreeting">Hello, <?php echo htmlspecialchars($store_name); ?>!</p>
      </div>

      <!-- Add Medicine -->
      <div id="addMedicine" class="section">
  <h2>Add Medicine</h2>
  <form id="addMedicineForm">
    <label>Serial No:</label>
    <input type="number" id="serialNo" placeholder="Serial Number" required />
    <label>Medicine Id:</label>
    <input type="number" id="medid" placeholder="Medicine Id" required/>
    <label>Quantity:</label>
    <input type="number" id="quantity" placeholder="Quantity" required/>
    <label>Medicine Name:</label>
    <input type="text" id="medName" placeholder="Medicine Name" required />
    <label>Stock Entry Date:</label>
    <input type="date" id="stockDate" required />
    <label>Manufacture Date:</label>
    <input type="date" id="mfgDate" required />
    <label>Expiry Date:</label>
    <input type="date" id="expDate" required />
    <label>Cost:</label>
    <input type="number" step="0.01" id="cost" placeholder="Cost" required />
    <label>Batch No:</label>
    <input type="text" id="batchNo" placeholder="Batch No" required />
    <label>Distributor Name:</label>
    <input type="text" id="distributorName" placeholder="Distributor Name" required />
    <button type="submit">Add</button>
  </form>
</div>


      <!-- About to Expire -->
      <div id="expiringStock" class="section">
        <h2>About to Expire Medicines:</h2>
        <table id="expireTable" border="1" cellpadding="5"></table>
      </div>

      <!-- Sold Medicines -->
      <div id="soldStock" class="section">
        <h2>Sold Medicines</h2>
        <table id="soldTable" border="1" cellpadding="5"></table>
      </div>

      <!-- Find Customer -->
      <div id="findCustomer" class="section">
  <h2>Find Customer</h2>
  <form id="findCustomerForm">
    <label>Enter Phone Number:</label>
    <input type="text" id="findCustomerPhone" placeholder="Enter Phone" required />
    <button type="submit">Find</button>
  </form>
  <div id="foundCustomerDetails"></div>
</div>


      <!-- Add Customer -->
      <div id="addCustomer" class="section">
  <h2>Add Customer</h2>
  <form id="addCustomerForm">
    <label>Name:</label>
    <input type="text" id="custName" name="name" placeholder="Name" required />
    <label>Phone:</label>
    <input type="text" id="custPhone" name="phone" placeholder="Phone" required />
    <label>Address:</label>
    <input type="text" id="custAddress" name="address" placeholder="Address" required />
    <button type="submit">Add Customer</button>
  </form>
</div>


 <!-- Present Stock -->
<div id="presentStock" class="section">
  <h2>Present Stock</h2>
  <table id="stockTable" border="1" cellpadding="5"></table>
</div>

<!-- All Expired Medicines -->
<div id="expiredmed" class="section">
  <h2>Expired but Unsold Medicines</h2>
  <table id="expiredUnsoldTable" border="1" cellpadding="5"></table>
</div>




    </div>
  </div>
  <script>
  const STORE_NAME = <?php echo json_encode($store_name); ?>;
</script>
  <!-- <script src="mpage.js"></script> -->
   <script src="mpage.js?v=<?php echo time(); ?>"></script>

</body>
</html>
