<?php
    session_start();
    require 'config.php';

    $productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : 0;
    if ($productId <= 0) {
        header('Location: cart.php');
        exit;
    }

    // fetch product
    $stmt = $conn->prepare("SELECT product_id, product_name, price, product_image FROM Products WHERE product_id = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $res = $stmt->get_result();
    if (!$res || $res->num_rows === 0) {
        header('Location: cart.php');
        exit;
    }
    $p = $res->fetch_assoc();
    $stmt->close();

    $_SESSION['cart'] = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];

    // if already in cart, increment qty
    $found = false;
    foreach ($_SESSION['cart'] as &$line) {
        if ((int)($line['id'] ?? 0) === (int)$p['product_id']) {
            $line['qty'] = isset($line['qty']) ? ((int)$line['qty'] + 1) : 2;
            $found = true;
            break;
        }
    }
    unset($line);

    if (!$found) {
        $_SESSION['cart'][] = [
            'id'    => (int)$p['product_id'],
            'name'  => (string)$p['product_name'],
            'img'   => $p['product_image'] ?: 'assets/placeholder.png',
            'price' => (float)$p['price'],
            'qty'   => 1,
        ];
    }

    header('Location: cart.php');
    exit;
?>