<?php
session_start();

// Get order from session (connected from admin.php)
$order = $_SESSION['order'] ?? [];
$paymentType = $_POST['paymentType'] ?? 'Cash'; // Or from session if needed, but keeping as is for now

// Define discount and tax
$discount = 0.10; // 10%
$tax = 0.12;      // 12%

$total = 0; // Initialize total

// Clear session after getting order
unset($_SESSION['order']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Upâyâ Café | Receipt</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
    font-family: 'Poppins', sans-serif;
    background: radial-gradient(circle at top left, #c7a27c, #7d5a3b);
    height: 100vh;
    margin: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.receipt {
    background: linear-gradient(180deg, #6b4b33 0%, #8a6a4c 100%);
    color: #f9e8d2;
    padding: 25px 30px;
    border-radius: 20px;
    width: 320px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
    text-align: center;
}

h2 { margin: 0; font-weight: 700; font-size: 1.5em; }
.subtext { font-size: 0.85em; opacity: 0.9; }
.divider { border-bottom: 1px solid rgba(255,255,255,0.2); margin: 10px 0 15px; }
.info { font-size: 0.85em; text-align: left; margin-bottom: 10px; }
table { width: 100%; font-size: 0.9em; border-collapse: collapse; margin-bottom: 10px; }
td { padding: 4px 0; }
td:nth-child(2), td:nth-child(3), td:nth-child(4) { text-align: right; }
.summary { text-align: right; font-size: 0.9em; margin-top: 8px; }
.summary div { margin: 3px 0; }
.total { font-weight: bold; font-size: 1.1em; margin-top: 8px; color: #fff; }
.footer { margin-top: 18px; font-size: 0.8em; opacity: 0.9; }
button {
    background: linear-gradient(to bottom, #f3d4b3, #dcb88a);
    color: #5c3b1f;
    font-weight: 600;
    border: none;
    padding: 8px 16px;
    border-radius: 10px;
    cursor: pointer;
    margin-top: 8px;
    transition: all 0.3s ease;
}
button:hover {
    background: linear-gradient(to bottom, #e9c69b, #cfa46e);
    transform: scale(1.03);
}
@media print {
    body { background: none; }
    button { display: none; }
    .receipt { box-shadow: none; background: #fff; color: #000; }
}
</style>
</head>
<body>
<div class="receipt">
    <h2>☕ Upâyâ Café</h2>
    <div class="subtext">loc: Bahay ni Jashney</div>
    <div class="subtext">Tel: CALL ME WHEN U NEED ME</div>

    <div class="divider"></div>

    <div class="info">
        <strong>Date:</strong> <?= date('Y-m-d H:i:s') ?><br>
        <strong>Cashier:</strong><br>
        <strong>Order #:</strong> <?= date('YmdHis') ?>
    </div>

    <table>
        <tbody>
        <?php foreach ($order as $item): 
            $name = htmlspecialchars($item['name']);
            $qty = (int)$item['qty'];
            $price = (float)$item['price'];
            $subtotal = $qty * $price;
            $total += $subtotal;
        ?>
        <tr>
            <td><?= $name ?></td>
            <td><?= $qty ?></td>
            <td>₱<?= number_format($price, 2) ?></td>
            <td>₱<?= number_format($subtotal, 2) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    $discountAmount = $total * $discount;
    $taxAmount = ($total - $discountAmount) * $tax;
    $grandTotal = $total - $discountAmount + $taxAmount;
    ?>

    <div class="summary">
        <div>Subtotal: ₱<?= number_format($total, 2) ?></div>
        <div>Discount (10%): -₱<?= number_format($discountAmount, 2) ?></div>
        <div>Tax (12%): ₱<?= number_format($taxAmount, 2) ?></div>
        <div class="total">Total: ₱<?= number_format($grandTotal, 2) ?></div>
        <div>Payment Type: <?= htmlspecialchars($paymentType) ?></div>
    </div>

    <div class="divider"></div>

    <div class="footer">
    <p>Thank you for visiting Upâyâ Café! <br> Follow us on <a href="https://www.facebook.com/upayacafeph" target="_blank" style="text-decoration: none;"> Facebook</a> </p>

        <button onclick="printAndRedirect()">🖨 Print Receipt</button>

<script>
function printAndRedirect() {
    window.print(); // opens the print dialog
}

// Redirect after printing
window.onafterprint = function() {
    // Change this to your main POS page
    window.location.href = "admin.php"; 
};
</script>

    </div>
</div>
</body>
</html>