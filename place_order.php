<?php
    session_start();
    include('config.php');

    $cart = $_SESSION['cart'] ?? [];
    if (!$cart) { header('Location: cart.php'); exit; }

    $user_id = $_SESSION['user_id'] ?? null; // null is fine for now

    $shipping_address = trim($_POST['shipping_address'] ?? '');
    $shipping_city    = trim($_POST['shipping_city'] ?? '');
    $shipping_state   = trim($_POST['shipping_state'] ?? '');
    $shipping_zip     = trim($_POST['shipping_zip'] ?? '');
    $shipping_country = trim($_POST['shipping_country'] ?? '');
    $payment_method   = $_POST['payment_method'] ?? '';

    if ($shipping_address==='' || $shipping_city==='' || $shipping_country==='') die('Missing shipping info.');
    if (!in_array($payment_method, ['UPI','CARD','COD'], true)) die('Invalid payment');

    $payment_status = ($payment_method === 'COD') ? 'Pending' : 'Paid';
    $status = 'Pending';

    // Group by store
    $byStore = [];
    foreach ($cart as $it) { $byStore[$it['store_id']][] = $it; }

    $conn->begin_transaction();
    try {
        foreach ($byStore as $store_id => $items) {
            $sql = "INSERT INTO Orders
                    (user_id, store_id, status, shipping_address, shipping_city, shipping_state,
                    shipping_zip, shipping_country, payment_method, payment_status)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $st = $conn->prepare($sql);
            $st->bind_param(
                "iissssssss",
                $user_id, $store_id, $status,
                $shipping_address, $shipping_city, $shipping_state, $shipping_zip, $shipping_country,
                $payment_method, $payment_status
            );
            $st->execute();
            $order_id = $st->insert_id;

            $is = $conn->prepare("INSERT INTO Order_Items (order_id, product_id, product_name, quantity, price, subtotal) VALUES (?,?,?,?,?,?)");
            foreach ($items as $p) {
                $sub = $p['price'] * $p['qty'];
                $is->bind_param("iisidd", $order_id, $p['product_id'], $p['name'], $p['qty'], $p['price'], $sub);
                $is->execute();

                // Optional stock decrement:
                $upd = $conn->prepare("UPDATE Products SET quantity = GREATEST(quantity - ?, 0) WHERE product_id = ?");
                $upd->bind_param("ii", $p['qty'], $p['product_id']);
                $upd->execute();
            }
        }
        $conn->commit();
        $_SESSION['cart'] = [];
        $_SESSION['flash_success'] = "Order placed successfully via {$payment_method}.";
        header("Location: thank_you.php?pm=".urlencode($payment_method));
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        die("Order failed: ".$e->getMessage());
    }
?>