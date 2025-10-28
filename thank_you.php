<?php
    session_start();
    include('config.php');
    $pm = $_GET['pm'] ?? 'Payment';
    $flash = $_SESSION['flash_success'] ?? '';
    unset($_SESSION['flash_success']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You – Aisle 24/7</title>
    <style>
        body{font-family:Inter,system-ui,Arial;background:#f7f7f7;margin:0}
        .center{max-width:640px;margin:60px auto;background:#fff;border:1px solid #eee;border-radius:12px;padding:24px;text-align:center}
        .btn{background:#F9A41E;border:none;color:#000;padding:10px 16px;border-radius:8px;font-weight:700;cursor:pointer;text-decoration:none}
        .success{background:#E9F7EF;border:1px solid #CDEFD8;color:#1E7E34;border-radius:8px;padding:12px;margin-bottom:14px}
    </style>
</head>
<body>
    <div class="center">
        <h2>🎉 Order Placed!</h2>
        <?php if ($flash): ?><div class="success"><?=htmlspecialchars($flash)?></div><?php endif; ?>
        <p>Your mock payment method: <strong><?=htmlspecialchars($pm)?></strong></p>
        <p>You can track the order in the seller dashboards (Orders tab).</p>
        <a class="btn" href="index.php">Continue shopping</a>
    </div>
</body>
</html>
