<?php
session_start();

// For demo: if no order exists, show a placeholder
$order = $_SESSION['order'] ?? [];

// Compute totals
$subtotal = 0.0;
foreach ($order as $item) {
    // expect item structure: ['name'=>'','price'=>float,'qty'=>int]
    $price = floatval($item['price'] ?? 0);
    $qty = intval($item['qty'] ?? 1);
    $subtotal += $price * $qty;
}

// Settings
$shipping_text = "Collect in store (Hermâ Wall Street)";
$shipping_cost = 0.00; // change if you charge shipping
$tax_rate = 0.12; // e.g. 12% VAT - change to your rate
$taxes = round($subtotal * $tax_rate, 2);
$total = round($subtotal + $shipping_cost + $taxes, 2);

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Checkout — Upâyâ Café</title>

  <!-- Fonts (same as POS) -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet" />

  <style>
    :root{
      --bg:#f7f2ec;
      --panel:#ffffff;
      --muted:#9b8f83;
      --accent:#b97f2f; /* warm amber */
      --accent-dark:#a46a2a;
      --border:#e1d6c9;
      --text:#2f2b27;
      --progress:#d7c7b7;
      font-family: 'Poppins', sans-serif;
    }

    body{
      margin:0;
      background:var(--bg);
      color:var(--text);
      -webkit-font-smoothing:antialiased;
    }

    .container{
      max-width:1100px;
      margin:26px auto;
      padding:28px;
    }

    /* header / progress */
    .topbar{
      display:flex;
      flex-direction:column;
      gap:14px;
      margin-bottom:12px;
      align-items:center;
    }
    .brand{
      font-family: 'Playfair Display', serif;
      font-weight:600;
      font-size:28px;
      letter-spacing:1px;
    }
    .progress{
      width:100%;
      background:transparent;
      padding:10px 0;
    }
    .steps{
      display:flex;
      align-items:center;
      gap:10px;
      width:100%;
    }
    .step{
      flex:1;
      text-align:center;
      position:relative;
      color:var(--muted);
      font-size:13px;
    }
    .step .dot{
      width:10px;height:10px;border-radius:50%;background:var(--border);margin:0 auto 6px;
    }
    .step.current .dot{ background:var(--accent-dark); }
    .progress-line{
      height:4px;background:var(--progress);border-radius:4px;margin-top:8px;position:relative;overflow:hidden;
    }
    .progress-line > .bar { height:100%; background:var(--accent); width:45%; }

    /* layout */
    .grid{
      display:grid;
      grid-template-columns: 1fr 360px;
      gap:22px;
      align-items:start;
    }

    /* left card (items) */
    .card{
      background:var(--panel);
      border:1px solid var(--border);
      border-radius:8px;
      padding:18px;
      box-shadow:0 2px 0 rgba(0,0,0,0.02);
    }

    .cart-header{ font-weight:600; color:var(--muted); margin-bottom:8px; font-size:13px;}
    .item-row{ display:flex; gap:14px; padding:14px 10px; border-bottom:1px dashed var(--border); align-items:center; }
    .item-row:last-child{ border-bottom:none; }
    .item-thumb{
      width:72px;height:56px;background:linear-gradient(180deg,#f1e9df,#fff);border-radius:6px;display:flex;align-items:center;justify-content:center;color:var(--muted);
      font-size:12px;border:1px solid var(--border);
    }
    .item-info{ flex:1; }
    .item-name{ font-weight:600; margin-bottom:6px;}
    .item-meta{ font-size:13px;color:var(--muted); }
    .item-price{ width:100px;text-align:right;font-weight:600;}

    /* right summary */
    .summary-side{ padding:18px; }
    .summary-title{ font-family: 'Playfair Display', serif; font-size:14px; letter-spacing:1px; margin-bottom:10px;}
    .summary-box{ background:var(--panel); border:1px solid var(--border); border-radius:8px; padding:18px; }
    .row{ display:flex; justify-content:space-between; margin:10px 0; color:var(--muted); }
    .row.total{ font-weight:700; color:var(--text); font-size:18px; margin-top:12px; border-top:1px dashed var(--border); padding-top:12px; }

    .orange-box{
      margin-top:14px;
      display:flex;
      gap:12px;
      align-items:start;
      background:#fff7ed;
      border:1px solid #f0d8bc;
      padding:12px;border-radius:6px;
    }
    .orange-box img{ width:68px;height:68px; object-fit:cover; border-radius:4px; border:1px solid #eed7b8; }
    .orange-box .txt{ font-size:13px; color:var(--muted); }

    .actions{ margin-top:16px; display:flex; gap:12px; }
    .btn{
      padding:10px 16px; border-radius:6px; border:none; cursor:pointer; font-weight:600;
    }
    .btn.cancel{ background:transparent; border:1px solid var(--border); color:var(--muted); }
    .btn.confirm{ background:var(--accent-dark); color:white; }

    /* payment radios */
    .payment{ margin-top:12px; }
    .payment label{ display:flex; gap:10px; align-items:center; padding:8px; border-radius:6px; border:1px solid var(--border); margin-bottom:8px; cursor:pointer; }
    .payment input{ accent-color:var(--accent-dark); }

    @media (max-width:900px){
      .grid{ grid-template-columns: 1fr; }
      .summary-side{ order:2; margin-top:12px; }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="topbar">
      <div class="brand">Upâyâ <span style="font-size:13px;color:var(--muted);font-family: Poppins, sans-serif;">Café — Checkout</span></div>

      <div class="progress">
        <div class="steps">
          <div class="step">
            <div class="dot"></div>
            Cart
          </div>
          <div class="step current">
            <div class="dot"></div>
            Checkout
          </div>
          <div class="step">
            <div class="dot"></div>
            Confirmation
          </div>
        </div>
        <div class="progress-line" aria-hidden="true"><div class="bar"></div></div>
      </div>
    </div>

    <div class="grid">
      <!-- LEFT: items / details -->
      <div class="card">
        <div class="cart-header">You have <?php echo count($order); ?> item<?php echo count($order) !== 1 ? 's' : ''; ?> in your cart.</div>

        <?php if (empty($order)): ?>
          <p style="color:var(--muted);">No items added yet. Go back to the POS and add items.</p>
        <?php else: ?>
          <?php foreach ($order as $idx => $it): 
                $name = htmlspecialchars($it['name'] ?? 'Item');
                $qty = intval($it['qty'] ?? 1);
                $price = number_format(floatval($it['price'] ?? 0), 2);
          ?>
            <div class="item-row">
              <div class="item-thumb"><h1>☕️</h1></div>
              <div class="item-info">
                <div class="item-name"><?php echo $name; ?></div>
                <div class="item-meta">Qty: <?php echo $qty; ?> · Ref: <?php echo sprintf('P%03d', $idx+1); ?></div>
              </div>
              <div class="item-price">₱ <?php echo number_format(floatval($it['price']) * $qty, 2); ?></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>

      </div>

      <!-- RIGHT: summary -->
      <div class="summary-side">
        <div class="summary-title">Upaya Cafe</div>
        <div class="summary-box">
          <div style="display:flex; justify-content:space-between; align-items:center;">
            <div>
              <div style="font-weight:700;"><?php echo count($order) ?: 0; ?> item<?php echo count($order) !== 1 ? 's' : ''; ?></div>
              <div style="font-size:13px;color:var(--muted); margin-top:6px;">All orders are Okay.</div>
            </div>
          </div>

          <div style="margin-top:12px;">
            <div class="row"><span>Subtotal</span><span>₱ <?php echo number_format($subtotal, 2); ?></span></div>
            <div class="row"><span>Taxes (<?php echo round($tax_rate*100); ?>%)</span><span>₱ <?php echo number_format($taxes, 2); ?></span></div>
            <div class="row total"><span>Total</span><span>₱ <?php echo number_format($total,2); ?></span></div>
          </div>

          <div class="payment">
            <div style="font-weight:700;margin-bottom:8px;">Payment</div>
            <form action="receipt.php" method="POST" id="checkoutForm">
              <label><input type="radio" name="paymentType" value="cash" checked /> Cash</label>
              <label><input type="radio" name="paymentType" value="card" /> Card</label>
              <label><input type="radio" name="paymentType" value="gcash" /> GCash</label>

              <!-- name=checkout is expected by your receipt.php processing -->
              <input type="hidden" name="checkout" value="1" />
              <div class="actions">
                <a href="admin.php" class="btn cancel" role="button">Back to POS</a>
                <button type="submit" class="btn confirm">Confirm & Proceed to Receipt</button>
              </div>
            </form>
          </div>

        </div>

        <div style="margin-top:12px; font-size:12px; color:var(--muted);">
          <strong>Customer Service</strong>
          <div style="margin-top:8px;">📞 Jashney's number · Mon-Fri 9am-6pm EST</div>
          <div style="margin-top:6px; color:var(--muted);">Jashney look alike ni Joshua Garcia</div>
        </div>

      </div>
    </div>
  </div>

  <script>
    // small UX: confirm before leave if there are items and user hasn't submitted
    (function(){
      const form = document.getElementById('checkoutForm');
      const hasItems = <?php echo empty($order) ? 'false' : 'true'; ?>;
      if(hasItems){
        window.addEventListener('beforeunload', function(e){
          // only show in case form not submitted
          if (!form.dataset.submitted) {
            e.returnValue = "Are you sure you want to leave? Your checkout hasn't been confirmed.";
            return e.returnValue;
          }
        });
        form.addEventListener('submit', function(){
          form.dataset.submitted = "1";
        });
      }
    })();
  </script>
</body>
</html>
 