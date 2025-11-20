<?php
session_start();
include __DIR__ . "/db/upaya_db.php";

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Fetch orders
$orders = [];
if (file_exists("orders.json")) {
    $raw = json_decode(file_get_contents("orders.json"), true);
    foreach ($raw as $key => $value) {
        if (is_array($value) && is_numeric($key)) {
            $orders[$key] = $value;
        }
    }
}

// Manager code for void authorization
$manager_code = 'admin123'; // Replace with secure code

// Handle void POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['prod_index'], $_POST['auth_code'])) {
    $orderId = $_POST['order_id'];
    $prodIndex = $_POST['prod_index'];
    $auth = $_POST['auth_code'];

    if ($auth === $manager_code) {
        if (isset($orders[$orderId]['order'][$prodIndex])) {
            $orders[$orderId]['order'][$prodIndex]['status'] = 'voided';
            // Recalculate total
            $total = 0;
            foreach ($orders[$orderId]['order'] as $item) {
                if (!isset($item['status']) || $item['status'] !== 'voided') {
                    $total += $item['price'] * $item['qty'];
                }
            }
            $orders[$orderId]['total'] = $total;
            file_put_contents('orders.json', json_encode($orders, JSON_PRETTY_PRINT));
        }
    }
    header("Location: orders.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Upâyâ Café | Orders</title>
<link rel="stylesheet" href="inventory.css" />
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
<style>
/* Void Button Styles */
button.void-btn {
    background: #ff4d4d;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 5px 10px;
    cursor: pointer;
    font-weight: 600;
    transition: 0.2s;
}
button.void-btn:hover { background: #e60000; }

/* Admin Auth Popup */
.auth-popup {
    display: none;
    position: fixed;
    top:0; left:0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
    z-index: 999;
}
.auth-popup .auth-box {
    background: white;
    padding: 20px 25px;
    border-radius: 10px;
    text-align: center;
    width: 300px;
}
.auth-popup input[type="password"] {
    width: 80%;
    padding: 6px;
    margin: 10px 0;
    border-radius: 4px;
    border: 1px solid #ccc;
}
.auth-popup button {
    padding: 6px 12px;
    margin-top: 5px;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    font-weight: 600;
}
.auth-popup button.submit-btn { background:#28a745; color:white; }
.auth-popup button.cancel-btn { background:#dc3545; color:white; margin-left:5px; }
</style>
</head>
<body>

<div class="pos-container">

  <div class="sidebar">
    <a href="admin.php" class="icon">🏠 HOME</a>
    <a href="inventory.php" class="icon">📦 INVENTORY</a>
    <a href="orders.php" class="icon active">📊 ORDERS</a>
    <a href="settings.php" class="icon">⚙️ SETTINGS</a>
  </div>

  <div class="main-content">
    <div class="logo">
      <h1>Upâyâ</h1>
      <p>Café</p>
    </div>

    <h1 class="title">Orders History</h1>

    <div class="inventory-box">
      <h2>Orders List</h2>

      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Order #</th>
              <th>Product</th>
              <th>Qty</th>
              <th>Price</th>
              <th>Subtotal</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
          <?php if(!empty($orders)): ?>
            <?php foreach($orders as $oid => $order): ?>
              <?php $itemCount = count($order['order']); ?>
              <?php foreach($order['order'] as $pid => $item): ?>
                <tr>
                  <?php if($pid === 0): ?>
                    <td rowspan="<?= $itemCount ?>"><?= str_pad($oid+1,6,'0',STR_PAD_LEFT) ?></td>
                  <?php endif; ?>
                  <td><?= htmlspecialchars($item['name']) ?></td>
                  <td><?= (int)$item['qty'] ?></td>
                  <td>₱<?= number_format((float)$item['price'],2) ?></td>
                  <td>₱<?= number_format((float)$item['qty']*(float)$item['price'],2) ?></td>
                  <td>
                    <?php if(isset($item['status']) && $item['status']==='voided'): ?>
                      <span class="status-voided">Voided</span>
                    <?php else: ?>
                      <span class="status-paid">Paid</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if(!isset($item['status']) || $item['status']!=='voided'): ?>
                        <button class="void-btn" data-order="<?= $oid ?>" data-item="<?= $pid ?>">Void</button>
                    <?php else: ?>
                        —
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" style="text-align:center;">No orders found.</td>
            </tr>
          <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Admin Authorization Popup -->
<div class="auth-popup" id="authPopup">
  <div class="auth-box">
    <h3>Enter Admin Password</h3>
    <input type="password" id="adminPass" placeholder="Admin Password" />
    <div>
      <button class="submit-btn" id="submitAuth">Submit</button>
      <button class="cancel-btn" id="cancelAuth">Cancel</button>
    </div>
  </div>
</div>

<script>
let currentOrder = null;
let currentItem = null;

document.querySelectorAll('.void-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        currentOrder = btn.dataset.order;
        currentItem = btn.dataset.item;
        document.getElementById('authPopup').style.display = 'flex';
    });
});

document.getElementById('cancelAuth').addEventListener('click', () => {
    document.getElementById('authPopup').style.display = 'none';
    document.getElementById('adminPass').value = '';
});

document.getElementById('submitAuth').addEventListener('click', () => {
    const pass = document.getElementById('adminPass').value;
    if(pass === '<?= $manager_code ?>'){
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'orders.php';

        const orderInput = document.createElement('input');
        orderInput.type = 'hidden';
        orderInput.name = 'order_id';
        orderInput.value = currentOrder;

        const itemInput = document.createElement('input');
        itemInput.type = 'hidden';
        itemInput.name = 'prod_index';
        itemInput.value = currentItem;

        const authInput = document.createElement('input');
        authInput.type = 'hidden';
        authInput.name = 'auth_code';
        authInput.value = pass;

        form.appendChild(orderInput);
        form.appendChild(itemInput);
        form.appendChild(authInput);

        document.body.appendChild(form);
        form.submit();
    } else {
        alert('Incorrect admin password!');
    }
});
</script>

</body>
</html>
