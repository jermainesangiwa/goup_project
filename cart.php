<?php
session_start();
include("config.php");

/**
 * Normalize the session cart so each line has:
 * id, name, img, price, qty
 */
if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
$normalized = [];
foreach ($_SESSION['cart'] as $it) {
    $normalized[] = [
        'id'    => isset($it['id']) ? (int)$it['id'] : (isset($it['product_id']) ? (int)$it['product_id'] : 0),
        'name'  => isset($it['name']) ? (string)$it['name'] : (isset($it['product_name']) ? (string)$it['product_name'] : 'Item'),
        'img'   => !empty($it['img']) ? (string)$it['img'] : (!empty($it['product_image']) ? (string)$it['product_image'] : 'assets/placeholder.png'),
        'price' => isset($it['price']) ? (float)$it['price'] : (isset($it['unit_price']) ? (float)$it['unit_price'] : 0.0),
        'qty'   => isset($it['qty']) ? max(1, (int)$it['qty']) : 1,
    ];
}
$_SESSION['cart'] = $normalized;

/** Handle quantity updates & removal (after normalization) */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'], $_POST['idx']) && ctype_digit((string)$_POST['idx'])) {
        $idx = (int)$_POST['idx'];
        if (isset($_SESSION['cart'][$idx])) {
            if ($_POST['action'] === 'inc') {
                $_SESSION['cart'][$idx]['qty'] = (int)$_SESSION['cart'][$idx]['qty'] + 1;
            } elseif ($_POST['action'] === 'dec') {
                $_SESSION['cart'][$idx]['qty'] = max(1, (int)$_SESSION['cart'][$idx]['qty'] - 1);
            } elseif ($_POST['action'] === 'remove') {
                array_splice($_SESSION['cart'], $idx, 1);
            }
        }
        header("Location: cart.php");
        exit;
    }
}

$cart = $_SESSION['cart']; // normalized

/** Totals */
$subtotal = 0.0;
foreach ($cart as $line) {
    $qty   = isset($line['qty']) ? (int)$line['qty'] : 1;
    $price = isset($line['price']) ? (float)$line['price'] : 0.0;
    $subtotal += $price * $qty;
}
$delivery = $subtotal > 0 ? 10.00 : 0.00;   // set your shipping rule here
$total    = $subtotal + $delivery;

/** Helper for quantity buttons */
function actionBtn($idx, $what, $label) {
    $idx  = (int)$idx;
    $what = htmlspecialchars($what, ENT_QUOTES, 'UTF-8');
    $labelSafe = htmlspecialchars($label, ENT_QUOTES, 'UTF-8');
    return '<form method="post" style="display:inline">
              <input type="hidden" name="idx" value="'.$idx.'">
              <input type="hidden" name="action" value="'.$what.'">
              <button class="qty-btn" type="submit">'.$labelSafe.'</button>
            </form>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>My Cart – Aisle 24/7</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    :root {
        --bg:#f5f6f8;
        --panel:#ffffff;
        --ink:#111;
        --muted:#6b7280;
        --brand:#F9A41E;
        --soft:#e5e7eb;
        --shadow:0 10px 30px rgba(0,0,0,.08);
        --chip:linear-gradient(135deg,#9D9D9D 0%,#7A7A7A 100%);
    }
    *{box-sizing:border-box;margin:0;padding:0}
    body{font-family:Poppins,system-ui,Arial,sans-serif;background:var(--bg);color:var(--ink);min-height:100vh}

    .wrap{max-width:820px;margin:20px auto;padding:0 16px}
    .card{background:var(--panel);border:1px solid var(--soft);border-radius:16px;box-shadow:var(--shadow);overflow:hidden}

    .header{display:flex;align-items:center;gap:12px;padding:14px 16px;background:var(--panel);border-bottom:1px solid var(--soft)}
    .back{display:inline-grid;place-items:center;width:36px;height:36px;border-radius:999px;background:rgba(0,0,0,.08)}
    .back::before{content:"";width:8px;height:8px;border:2px solid var(--ink);border-right:none;border-bottom:none;transform:rotate(-45deg)}
    .title{font-weight:600;font-size:20px;margin-left:4px}

    .list{padding:16px}
    .item{display:flex;gap:14px;align-items:center;padding:12px;border:1px solid var(--soft);border-radius:12px;margin-bottom:12px}
    .thumb{width:84px;height:72px;border-radius:8px;overflow:hidden;background:#eee;flex-shrink:0}
    .thumb img{width:100%;height:100%;object-fit:cover}
    .meta{flex:1;min-width:0}
    .name{font-weight:600;font-size:15px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .controls{display:flex;align-items:center;gap:10px;margin-top:8px}
    .qty-btn{width:28px;height:28px;border-radius:999px;border:1px solid #00000033;background:var(--chip);color:#fff;font-weight:700;cursor:pointer}
    .qty{min-width:20px;text-align:center;font-weight:600}
    .line-total{font-weight:600}

    .empty{padding:20px;margin:20px 0;border-radius:12px;background:#f3f4f6;color:#374151;border:1px dashed #d1d5db;text-align:center}
    .empty a{color:#111;text-decoration:underline}

    .summary{padding:12px 16px;border-top:1px solid var(--soft)}
    .row{display:flex;justify-content:space-between;padding:8px 0;color:#111}
    .row.total{font-weight:700;border-top:1px solid var(--soft);margin-top:8px}

    .checkout-bar{position:sticky;bottom:0;background:var(--panel);border-top:1px solid var(--soft);display:flex;justify-content:space-between;align-items:center;padding:12px 16px}
    .to{color:var(--muted);font-size:13px}
    .btn{background:var(--brand);border:none;color:#000;font-weight:700;padding:12px 18px;border-radius:10px;cursor:pointer}
    .btn:disabled{opacity:.5;cursor:not-allowed}

    @media(min-width:680px){
        .title{font-size:22px}
        .btn{padding:12px 24px}
    }
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <div class="header">
      <a class="back" href="javascript:history.back()" aria-label="Go back"></a>
      <div class="title">My Cart</div>
    </div>

    <div class="list">
      <?php if (empty($cart)): ?>
        <div class="empty">
          <strong>Your cart is empty.</strong><br>
          <a href="grocery essentials.php">Return to shop</a>
        </div>
      <?php else: ?>
        <?php foreach ($cart as $i => $line): ?>
          <?php
            $img   = htmlspecialchars($line['img']   ?? 'assets/placeholder.png');
            $name  = htmlspecialchars($line['name']  ?? 'Item');
            $qty   = (int)($line['qty']   ?? 1);
            $price = (float)($line['price'] ?? 0);
            $lineTotal = $price * $qty;
          ?>
          <article class="item">
            <div class="thumb"><img src="<?=$img?>" alt=""></div>
            <div class="meta">
              <div class="name"><?=$name?></div>
              <div class="controls">
                <?=actionBtn($i,'dec','−')?>
                <div class="qty"><?=$qty?></div>
                <?=actionBtn($i,'inc','+')?>
                <?=actionBtn($i,'remove','🗑')?>
              </div>
            </div>
            <div class="line-total">₹<?=number_format($lineTotal, 2)?></div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="summary">
      <div class="row"><span>Subtotal</span><span>₹<?=number_format($subtotal,2)?></span></div>
      <div class="row"><span>Delivery</span><span>₹<?=number_format($delivery,2)?></span></div>
      <div class="row total"><span>Total</span><span>₹<?=number_format($total,2)?></span></div>
    </div>

    <div class="checkout-bar">
      <div class="to">Deliver to: (add on next step)</div>
      <form action="checkout.php" method="get">
        <button class="btn" type="submit" <?=empty($cart)?'disabled':''?>>Check Out</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
