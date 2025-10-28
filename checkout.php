<?php
session_start();
include('config.php'); // DB connection

$cart = $_SESSION['cart'] ?? [];
if (!$cart) { header('Location: cart.php'); exit; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout – Aisle 24/7</title>
    <style>
        body{font-family:Inter,system-ui,Arial;background:#f7f7f7;margin:0}
        .wrap{max-width:760px;margin:24px auto;background:#fff;border:1px solid #eee;border-radius:10px;padding:20px}
        h2{margin:0 0 14px}
        label{font-weight:600}
        input,select{width:100%;padding:10px;border:1px solid #ccc;border-radius:6px;margin:6px 0 14px}
        .btn{background:#F9A41E;border:none;color:#000;padding:10px 16px;border-radius:8px;font-weight:700;cursor:pointer}
        .summary{background:#fafafa;border:1px dashed #ddd;border-radius:8px;padding:10px 12px;margin:12px 0}
    </style>
</head>
<body>
    <div class="wrap">
        <h2>Shipping & Payment</h2>
        
        <div class="summary">
            <strong>Items:</strong>
            <ul style="margin:8px 0 0 18px">
            <?php
                $total = 0;
                foreach ($cart as $c) { $line = $c['price']*$c['qty']; $total += $line;
                echo "<li>".htmlspecialchars($c['name'])." x{$c['qty']} – ₹".number_format($line,2)."</li>";
                }
            ?>
            </ul>
            <p><strong>Total:</strong> ₹<?=number_format($total,2)?></p>
        </div>

        <form method="post" action="place_order.php">
            <label>Address</label>
            <input name="shipping_address" required placeholder="Hostel/Building, Street">

            <label>City</label>
            <input name="shipping_city" required placeholder="City">

            <label>State (optional)</label>
            <input name="shipping_state" placeholder="State">

            <label>ZIP (optional)</label>
            <input name="shipping_zip" placeholder="ZIP/Pin">

            <label>Country</label>
            <input name="shipping_country" value="India" required>

            <label>Payment Method (Mock)</label>
            <select name="payment_method" required>
            <option value="UPI">UPI (Mock)</option>
            <option value="CARD">Card (Mock)</option>
            <option value="COD">Cash on Delivery (Mock)</option>
            </select>

            <button class="btn" type="submit">Place Order</button>
        </form>
    </div>
</body>
</html>
