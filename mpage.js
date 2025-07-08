function showSection(sectionId) {
  document.querySelectorAll('.section').forEach(section => {
    section.classList.remove('active');
  });

  const section = document.getElementById(sectionId);
  if (section) section.classList.add('active');

  // Reload data for dynamic sections
  if (sectionId === 'expiredmed') loadExpiredUnsoldTable();
  else if (sectionId === 'presentStock') loadStockTable();
  else if (sectionId === 'soldStock') loadSalesTable();
  else if (sectionId === 'expiringStock') loadExpiringTable();
}


function toggleDropdown() {
  document.getElementById('dropdown').classList.toggle('hidden');
}

function logout() {
  alert("Logging out...");
  window.location.href = "login.php";
}

document.addEventListener('DOMContentLoaded', function () {
  const userEmail = STORE_NAME;



  // --- ADD MEDICINE ---
  document.getElementById('addMedicineForm').addEventListener('submit', async function (e) {
    e.preventDefault();
    
    const sl_no = document.getElementById('serialNo').value;
    const medicine_name = document.getElementById('medName').value;
    const stock_entry_date = document.getElementById('stockDate').value;
    const mfg_date = document.getElementById('mfgDate').value;
    const exp_date = document.getElementById('expDate').value;
    const cost = document.getElementById('cost').value;
    const batch_no = document.getElementById('batchNo').value;
    const distributorName = document.getElementById('distributorName').value;
    const quantity = document.getElementById('quantity').value;
    const medid = document.getElementById('medid').value;
    const formData = new FormData();
    formData.append('sl_no', sl_no);
    formData.append('medicine_name', medicine_name);
    formData.append('stock_entry_date', stock_entry_date);
    formData.append('mfg_date', mfg_date);
    formData.append('exp_date', exp_date);
    formData.append('cost', cost);
    formData.append('batch_no', batch_no);
    formData.append('distributor_name', distributorName);
    formData.append('medid', medid);
    formData.append('quantity', quantity);


    try {
    const res = await fetch('add_medicine.php', {
            method: 'POST',
            body: formData
        });

    const text = await res.text();
    
    if (text === 'MEDICINE_ADDED') {
        alert('Medicine added successfully!');
        document.getElementById('addMedicineForm').reset();
    } else if (text === 'MISSING_FIELDS') {
        alert('Please fill in all fields.');
    } else if (text === 'UNAUTHORIZED') {
        alert('You are not logged in. Redirecting to login...');
        window.location.href = 'login.html';
    } else if (text.startsWith('ERROR')) {
        alert('Server error: ' + text);
    } else {
        alert('Unexpected response: ' + text);
    }
} catch (err) {
    alert('Network error: ' + err.message);
}

      });

  // --- ADD CUSTOMER ---
  const addCustomerForm = document.getElementById('addCustomerForm');
if (addCustomerForm) {
  addCustomerForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const formData = new FormData(addCustomerForm);
    fetch('add_customer.php', { method: 'POST', body: formData })
      .then(res => res.text())
      .then(msg => {
        if (msg === 'CUSTOMER_ADDED') {
          alert("Customer added successfully.");
        } else if (msg === 'EXISTS') {
          alert("Customer already exists.");
        } else {
          alert("Error adding customer.");
        }
        addCustomerForm.reset();
      })
      .catch(() => alert("Error adding customer."));
  });
}


  // --- FIND CUSTOMER ---
 document.getElementById('findCustomerForm').addEventListener('submit', function (e) {
  e.preventDefault();
  const phone = document.getElementById('findCustomerPhone').value;
  const formData = new FormData();
  formData.append('phone', phone);

  fetch('find_customer.php', {
    method: 'POST',
    body: formData
  })
    .then(res => res.json())
    .then(data => {
      const resultDiv = document.getElementById('foundCustomerDetails');
      if (data.error === "NOT_FOUND") {
        resultDiv.innerHTML = "Customer not found.";
        return;
      }

      if (data.customer) {
        const customer = data.customer;
        let html = `<strong>Name:</strong> ${customer.name}<br>
                    <strong>Phone:</strong> ${customer.phone}<br>
                    <strong>Address:</strong> ${customer.address}<br><br>
                    <strong>Purchase History:</strong><ul>`;

        if (data.purchases.length === 0) {
          html += "<li>No previous purchases.</li>";
        } else {
          data.purchases.forEach(p => {
            html += `<li>${p.medicine_name} — ${p.quantity} pcs — ${p.sold_at}</li>`;
          });
        }

        html += `</ul>
                <h3>Add Medicine Purchase:</h3>
                <form id="sellForm">
                  <label>Medicine ID:</label>
                  <input type="number" id="sellMedID" placeholder="Medicine Id" required /><br>
                  <label>Quantity:</label>
                  <input type="number" id="sellQty" placeholder="Quantity" required /><br>
                  <input type="hidden" id="custId" value="${customer.id}" />
                  <button type="submit">Sell</button>
                </form>
                <div id="sellStatus"></div>`;

        resultDiv.innerHTML = html;

        document.getElementById('sellForm').addEventListener('submit', function (e) {
          e.preventDefault();

          const customer_id = document.getElementById('custId').value;
          const medicine_id = document.getElementById('sellMedID').value;
          const quantity = document.getElementById('sellQty').value;

          const sellFormData = new FormData();
          sellFormData.append('customer_id', customer_id);
          sellFormData.append('medicine_id', medicine_id);
          sellFormData.append('quantity', quantity);

          fetch('sell_medicine.php', {
            method: 'POST',
            body: sellFormData
          })
            .then(res => res.text())
            .then(result => {
              const statusDiv = document.getElementById('sellStatus');
              if (result === "SOLD_SUCCESS") {
                statusDiv.innerHTML = "<span style='color:green'>Medicine sold successfully!</span>";
                document.getElementById('sellForm').reset();
              } else if (result === "NOT_ENOUGH_STOCK") {
                statusDiv.innerHTML = "<span style='color:red'>Not enough stock available.</span>";
              } else {
                statusDiv.innerHTML = "<span style='color:red'>Error: " + result + "</span>";
              }
            })
            .catch(err => alert("Sale failed: " + err.message));
        });
      } else {
        resultDiv.innerHTML = "Customer not found.";
      }
    })
    .catch(() => alert("Error finding customer."));
});



  // --- LOAD TABLES ---
  loadExpiringTable(userEmail);
  loadStockTable(userEmail);
  loadSalesTable(userEmail);
  loadAllSalesTable(userEmail);
  loadExpiredUnsoldTable(userEmail);

});

// Load "About to Expire"
function loadExpiringTable() {
  fetch('get_expiring.php')
    .then(res => res.json())
    .then(data => {
      const table = document.getElementById('expireTable');
      table.innerHTML = `<tr><th>Sl No</th><th>Medicine</th><th>Expiry Date</th><th>Batch</th><th>Cost</th></tr>`;
      
      if (!data.length) {
        table.innerHTML += '<tr><td colspan="5">No expiring medicines</td></tr>';
        return;
      }

      data.forEach(med => {
        table.innerHTML += `<tr>
          <td>${med.sl_no}</td>
          <td>${med.medicine_name}</td>
          <td>${med.exp_date}</td>
          <td>${med.batch_no}</td>
          <td>₹${med.cost}</td>
        </tr>`;
      });
    })
    .catch(err => {
      console.error('Error loading expiring medicines:', err);
    });
}


// Load "Present Stock"
function loadStockTable() {
  fetch('get_stock.php')
    .then(res => res.json())
    .then(data => {
      const table = document.getElementById('stockTable');
      table.innerHTML = `<tr>
        <th>MedID</th>
        <th>Name</th>
        <th>Available Qty</th>
        <th>Sold Qty</th>
        <th>Cost/Unit</th>
        <th>Mfg Date</th>
        <th>Exp Date</th>
        <th>Batch No</th>
        <th>Distributor</th>
      </tr>`;

      if (!data.length || data.error) {
        table.innerHTML += `<tr><td colspan="9">No available stock</td></tr>`;
        return;
      }

      data.forEach(med => {
        table.innerHTML += `<tr>
          <td>${med.medid}</td>
          <td>${med.medicine_name}</td>
          <td>${med.total_quantity}</td>
          <td>${med.total_sold}</td>
          <td>₹${parseFloat(med.cost).toFixed(2)}</td>
          <td>${med.mfg_date}</td>
          <td>${med.exp_date}</td>
          <td>${med.batch_no}</td>
          <td>${med.distributor_name}</td>
        </tr>`;
      });
    });
}


// Load "Sold Medicines"
function loadSalesTable() {
  fetch('get_sold_medicines.php')
    .then(res => res.json())
    .then(data => {
      const table = document.getElementById('soldTable');
      table.innerHTML = `<tr>
        <th>Sold Date</th>
        <th>Medicine</th>
        <th>Quantity</th>
        <th>Customer</th>
        <th>Phone</th>
      </tr>`;

      if (!data.length) {
        table.innerHTML += '<tr><td colspan="5">No sales yet</td></tr>';
        return;
      }

      data.forEach(sale => {
        table.innerHTML += `<tr>
          <td>${sale.sold_at}</td>
          <td>${sale.med_name}</td>
          <td>${sale.quantity}</td>
          <td>${sale.cust_name}</td>
          <td>${sale.phone}</td>
        </tr>`;
      });
    })
    .catch(err => {
      console.error('Error loading sales:', err);
    });
}


// Load "All Expired Medicines"
function loadExpiredUnsoldTable() {
  fetch('get_expired_unsold.php')
    .then(res => res.json())
    .then(data => {
      const table = document.getElementById('expiredUnsoldTable');
      let html = `<tr>
        <th>Medicine Name</th>
        <th>Sl No</th>
        <th>Batch No</th>
        <th>Mfg Date</th>
        <th>Exp Date</th>
        <th>Quantity</th>
        <th>Cost</th>
      </tr>`;

      // Handle no data
      if (!Array.isArray(data) || data.length === 0 || data.error) {
        html += `<tr><td colspan="7" style="text-align:center;">No expired unsold medicines found.</td></tr>`;
        table.innerHTML = html;
        return;
      }

      // Append data
      data.forEach(med => {
        html += `<tr>
          <td>${med.medicine_name}</td>
          <td>${med.sl_no}</td>
          <td>${med.batch_no}</td>
          <td>${med.mfg_date}</td>
          <td>${med.exp_date}</td>
          <td>${med.quantity}</td>
          <td>₹${parseFloat(med.cost).toFixed(2)}</td>
        </tr>`;
      });

      table.innerHTML = html;
    })
    .catch(() => {
      const table = document.getElementById('expiredUnsoldTable');
      table.innerHTML = `<tr><td colspan="7">Error loading data.</td></tr>`;
    });
}