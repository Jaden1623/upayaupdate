<?php
session_start();

// Load all saved orders
$orders = [];
if (file_exists("orders.json")) {
    $orders = json_decode(file_get_contents("orders.json"), true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upâyâ Café | Orders</title>
  <link rel="stylesheet" href="pos.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />

  <style>
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #3a2818;
        margin: 0;
        padding: 0;
    }

    .orders-container {
        width: calc(100% - 80px);
        margin-left: 80px;
        padding: 25px;
    }

    .orders-title {
        font-size: 28px;
        margin-bottom: 20px;
        font-weight: 600;
        color: #2a2a2a;
    }

    /* TABLE WRAPPER */
    .orders-table-wrapper {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    thead {
        background: #3a2818;
        color: white;
    }

    th {
        padding: 14px 10px;
        text-align: left;
        font-weight: 500;
        font-size: 14px;
    }

    td {
        padding: 14px 10px;
        border-bottom: 1px solid #c7a989;
        font-size: 14px;
        color: #333;
    }

    tr:hover td {
        background: #c7a989;
    }

    .status-paid {
        background: #c7a989;
        color: #155724;
        padding: 6px 10px;
        font-size: 12px;
        border-radius: 6px;
        font-weight: 500;
    }

    .view-btn {
        padding: 8px 12px;
        background: #222;
        color: white;
        text-decoration: none;
        border-radius: 6px;
        font-size: 12px;
        transition: 0.2s;
    }

    .view-btn:hover {
        background: #000;
    }

  </style>
</head>
<body>

<div class="logo">
    <h1>Upâyâ</h1>
    <p>Café</p>
</div>

<div class="pos-container">

    <!-- Sidebar same colors -->
    <div class="sidebar">
        <a href="admin.php" class="icon active">🏠</a>
        <a href="orderhistory.php" class="icon active">📝</a>
        <a href="inventory.php" class="icon active">📦</a>
        <a href="salesreport.php" class="icon active">📊</a>
        <a href="settings.php" class="icon active">⚙️</a>
        <a href="logout.php" class="icon active">⬅️</a> 
    </div>

    <div class="orders-container">
        <h2 class="orders-title">Orders History</h2>

        <div class="orders-table-wrapper">

        <?php if (!empty($orders)): ?>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Receipt</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($orders as $index => $order): ?>
                <tr>
                    <td>#<?= $index + 1 ?></td>
                    <td>₱<?= number_format($order['total'], 2) ?></td>
                    <td><?= htmlspecialchars($order['payment']) ?></td>
                    <td><?= htmlspecialchars($order['timestamp']) ?></td>
                    <td><span class="status-paid">Paid</span></td>
                    <td><a class="view-btn" href="orders_view.php?id=<?= $index ?>">View</a></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php else: ?>
            <p>No orders found.</p>
        <?php endif; ?>

        </div>
    </div>

</div>

</body>
</html>
