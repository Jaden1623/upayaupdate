 <?php
// salesreport.php
session_start();

// Load orders.json
$ordersFile = 'orders.json';
$orders = [];
if (file_exists($ordersFile)) {
    $raw = file_get_contents($ordersFile);
    $orders = json_decode($raw, true);
    if (!is_array($orders)) $orders = [];
}

// Helper: parse date string to timestamp (or null)
function to_ts($datetime) {
    $ts = strtotime($datetime);
    return $ts === false ? null : $ts;
}

// Filters from GET
$date_from_raw = $_GET['date_from'] ?? '';
$date_to_raw   = $_GET['date_to'] ?? '';
$payment_filter = strtolower($_GET['payment'] ?? 'all');

// Normalize date inputs (YYYY-MM-DD expected)
$date_from_ts = $date_from_raw ? strtotime($date_from_raw . ' 00:00:00') : null;
$date_to_ts   = $date_to_raw ? strtotime($date_to_raw . ' 23:59:59') : null;

// Apply filters and compute stats
$filtered = [];
$total_sales = 0.0;
$total_orders = 0;
$cash_sales = 0.0;
$gcash_sales = 0.0;

foreach ($orders as $idx => $ord) {
    // Expect structure: ['order'=> [...], 'total'=> 123, 'payment'=>'cash', 'timestamp'=>'YYYY-MM-DD HH:MM:SS']
    $ts = isset($ord['timestamp']) ? to_ts($ord['timestamp']) : null;
    if ($date_from_ts && $ts !== null && $ts < $date_from_ts) continue;
    if ($date_to_ts && $ts !== null && $ts > $date_to_ts) continue;
    if ($payment_filter !== 'all' && isset($ord['payment']) && strtolower($ord['payment']) !== $payment_filter) continue;

    // valid
    $filtered[] = array_merge($ord, ['_index' => $idx]);
    $total_sales += floatval($ord['total'] ?? 0);
    $total_orders++;
    $p = strtolower($ord['payment'] ?? '');
    if ($p === 'cash') $cash_sales += floatval($ord['total'] ?? 0);
    if ($p === 'gcash') $gcash_sales += floatval($ord['total'] ?? 0);
}

// CSV export support for current filtered result
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Build CSV
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=sales_report.csv');
    $out = fopen('php://output', 'w');
    // Header row
    fputcsv($out, ['Index','Timestamp','Items Count','Items (name:qty:price)','Total','Payment']);
    foreach ($filtered as $f) {
        $items = $f['order'] ?? [];
        $itemsDesc = [];
        foreach ($items as $it) {
            $iname = $it['name'] ?? '';
            $iqty = $it['qty'] ?? 0;
            $iprice = $it['price'] ?? 0;
            $itemsDesc[] = "{$iname}:{$iqty}:{$iprice}";
        }
        fputcsv($out, [
            $f['_index'],
            $f['timestamp'] ?? '',
            count($items),
            implode(' | ', $itemsDesc),
            number_format(floatval($f['total'] ?? 0), 2, '.', ''),
            $f['payment'] ?? ''
        ]);
    }
    fclose($out);
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Upâyâ Café | Sales Report</title>
  <link rel="stylesheet" href="menu.css" />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@1,600&family=Poppins:wght@400;500&display=swap" rel="stylesheet" />
  <style>
    /* Minimal extra styles for the report cards & table (keeps the look consistent) */
    .report-controls {
      display:flex;
      gap:12px;
      align-items:center;
      margin-bottom:12px;
      flex-wrap:wrap;
    }
    .summary-cards {
      display:flex;
      gap:12px;
      margin-bottom:14px;
      flex-wrap:wrap;
    }
    .card {
      background: linear-gradient(180deg,#fffef9,#f6efe0);
      border-radius:12px;
      padding:12px 16px;
      box-shadow: 0 6px 20px rgba(0,0,0,0.08);
      min-width:150px;
      flex:1;
    }
    .card h4 { margin-bottom:6px; font-size:14px; color:#3b2417; font-family:'Georgia', serif; font-style:italic; }
    .card p { font-size:20px; font-weight:700; color:#3b2417; }
    .filters input[type="date"], .filters select {
      padding:8px 10px;
      border-radius:8px;
      border:1px solid #d1bfa8;
    }
    .quick-buttons button {
      padding:8px 10px;
      border-radius:8px;
      border:none;
      cursor:pointer;
      background:#f2e2ce;
      margin-right:6px;
    }
    .report-table {
      width:100%;
      border-collapse:collapse;
      margin-top:10px;
      background: rgba(255,255,255,0.85);
      border-radius:8px;
      overflow:hidden;
      box-shadow: 0 6px 18px rgba(0,0,0,0.08);
    }
    .report-table th, .report-table td {
      padding:10px 12px;
      border-bottom:1px solid rgba(0,0,0,0.06);
      text-align:left;
      font-size:13px;
    }
    .report-table th {
      background: rgba(0,0,0,0.02);
      font-weight:600;
    }
    .small-btn {
      padding:6px 10px;
      border-radius:8px;
      border:none;
      cursor:pointer;
      font-weight:600;
    }
    .export-btn { background:#6b4423; color:#fff; }
    .view-btn { background:#c9977c; color:#fff; }
    .no-data { padding:20px; text-align:center; color:#3b2417; }
    @media (max-width:900px){
      .summary-cards { flex-direction:column; }
    }
  </style>
</head>
<body>

  <div class="logo">
    <h1>Upâyâ</h1>
    <p>Café</p>
  </div>

  <div class="pos-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <a href="admin.php" class="icon">🏠</a>
      <a href="orderhistory.php" class="icon">📝</a>
      <a href="inventory.php" class="icon">📦</a>
      <a href="salesreport.php" class="icon active">📊</a>
      <a href="settings.php" class="icon">⚙️</a>
      <a href="logout.php" class="icon">⬅️</a>
    </div>

    <!-- Main menu / content -->
    <div class="menu-section">
      <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
        <h2 style="font-family:'Playfair Display', serif; font-style:italic; color:#fff8ec;">Sales Report</h2>

        <div>
          <a href="salesreport.php?<?php
            // Keep current GET except export
            $qs = $_GET;
            $qs['export'] = 'csv';
            echo htmlentities(http_build_query($qs));
          ?>" class="small-btn export-btn" style="text-decoration:none;padding:8px 12px;border-radius:8px;color:#fff;">Export CSV</a>
        </div>
      </div>

      <!-- Filters -->
      <div class="report-controls">
        <div class="filters" style="display:flex;gap:8px;align-items:center;">
          <label style="color:#fff8ec;margin-right:6px;">From</label>
          <input type="date" id="date_from" name="date_from" value="<?= htmlspecialchars($date_from_raw) ?>">
          <label style="color:#fff8ec;margin-left:12px;margin-right:6px;">To</label>
          <input type="date" id="date_to" name="date_to" value="<?= htmlspecialchars($date_to_raw) ?>">
          <select id="payment_filter" name="payment">
            <option value="all" <?= $payment_filter==='all' ? 'selected' : '' ?>>All Payments</option>
            <option value="cash" <?= $payment_filter==='cash' ? 'selected' : '' ?>>Cash</option>
            <option value="gcash" <?= $payment_filter==='gcash' ? 'selected' : '' ?>>GCash</option>
          </select>
          <button id="applyFilters" class="small-btn" style="background:#2d3436;color:#fff;margin-left:6px;">Apply</button>
        </div>

        <div class="quick-buttons" style="margin-left:auto;">
          <button type="button" data-range="today">Today</button>
          <button type="button" data-range="yesterday">Yesterday</button>
          <button type="button" data-range="week">This Week</button>
          <button type="button" data-range="month">This Month</button>
        </div>
      </div>

      <!-- Summary cards -->
      <div class="summary-cards">
        <div class="card">
          <h4>Total Sales</h4>
          <p>₱ <?= number_format($total_sales, 2) ?></p>
        </div>
        <div class="card">
          <h4>Total Orders</h4>
          <p><?= number_format($total_orders) ?></p>
        </div>
        <div class="card">
          <h4>Cash Sales</h4>
          <p>₱ <?= number_format($cash_sales, 2) ?></p>
        </div>
        <div class="card">
          <h4>GCash Sales</h4>
          <p>₱ <?= number_format($gcash_sales, 2) ?></p>
        </div>
      </div>

      <!-- Table -->
      <?php if (count($filtered) === 0): ?>
        <div class="no-data">No orders found for the selected filters.</div>
      <?php else: ?>
        <table class="report-table">
          <thead>
            <tr>
              <th>#</th>
              <th>Timestamp</th>
              <th>Items</th>
              <th>Items Count</th>
              <th>Total (₱)</th>
              <th>Payment</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($filtered as $f): 
                $items = $f['order'] ?? [];
                $itemsCount = count($items);
                $idx = $f['_index'];
            ?>
              <tr>
                <td><?= htmlentities($idx) ?></td>
                <td><?= htmlentities($f['timestamp'] ?? '') ?></td>
                <td>
                  <?php
                    $desc = [];
                    foreach ($items as $it) {
                      $desc[] = htmlspecialchars($it['name'] ?? '') . ' x' . intval($it['qty'] ?? 0);
                    }
                    echo nl2br(htmlentities(implode(" \n", $desc)));
                  ?>
                </td>
                <td><?= $itemsCount ?></td>
                <td><?= number_format(floatval($f['total'] ?? 0), 2) ?></td>
                <td><?= htmlspecialchars($f['payment'] ?? '') ?></td>
                <td>
                  <a class="small-btn view-btn" style="text-decoration:none;color:#fff;" href="receipt.php?index=<?= urlencode($idx) ?>" target="_blank">View Receipt</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>

    </div>

    <!-- Right column: keep order-summary look but show quick stats / legend -->
    <div class="order-summary" style="min-width:260px;">
      <h3>Report Info</h3>
      <div style="padding:12px;">
        <p style="color:#3b2417;font-weight:600;">Filtered Orders: <?= $total_orders ?></p>
        <p style="color:#3b2417;">Total Revenue: ₱ <?= number_format($total_sales,2) ?></p>
        <hr style="margin:12px 0;border:none;border-top:1px solid rgba(0,0,0,0.06);" />
        <p style="font-size:13px;color:#3b2417;">Tip: Use the "Export CSV" button to download the filtered data.</p>
      </div>
    </div>

  </div>

<script>
  // JS: apply filters by reloading page with query params
  document.getElementById('applyFilters').addEventListener('click', function(){
    const from = document.getElementById('date_from').value;
    const to = document.getElementById('date_to').value;
    const payment = document.getElementById('payment_filter').value;
    const params = new URLSearchParams(window.location.search);
    if (from) params.set('date_from', from); else params.delete('date_from');
    if (to) params.set('date_to', to); else params.delete('date_to');
    if (payment) params.set('payment', payment); else params.delete('payment');
    // remove export if present
    params.delete('export');
    window.location.search = params.toString();
  });

  // Function to set date range based on range type
  function setDateRange(range) {
    const today = new Date();
    let from, to;
    if (range === 'today') {
      from = to = today;
    } else if (range === 'yesterday') {
      const y = new Date(today);
      y.setDate(y.getDate() - 1);
      from = to = y;
    } else if (range === 'week') {
      const start = new Date(today);
      const day = start.getDay(); // 0-6 (Sun-Sat)
      const diff = start.getDate() - day + (day === 0 ? -6 : 1); // Monday start
      start.setDate(diff);
      from = start;
      to = today;
    } else if (range === 'month') {
      const start = new Date(today.getFullYear(), today.getMonth(), 1);
      from = start;
      to = today;
    }
    if (from && to) {
      document.getElementById('date_from').value = formatDate(from);
      document.getElementById('date_to').value = formatDate(to);
      document.getElementById('applyFilters').click();
    }
  }

  // Helper function to format date
  function formatDate(d) {
    const y = d.getFullYear();
    const m = String(d.getMonth()+1).padStart(2,'0');
    const day = String(d.getDate()).padStart(2,'0');
    return `${y}-${m}-${day}`;
  }

  // Quick range buttons
  document.querySelectorAll('.quick-buttons button').forEach(btn=>{
    btn.addEventListener('click', function(){
      const range = btn.getAttribute('data-range');
      setDateRange(range);
    });
  });
</script>

</body>
</html>
