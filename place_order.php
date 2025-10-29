<?php
    // place_order.php
    session_start();
    require_once 'config.php';

    // 1) Require login & non-empty cart
    if (!isset($_SESSION['user_id'])) {
        header('Location: user_login.php');
        exit;
    }
    $userId = (int)$_SESSION['user_id'];

    $cart = $_SESSION['cart'] ?? [];
    if (!$cart || !is_array($cart)) {
        header('Location: cart.php');
        exit;
    }

    // (Optional) collect checkout fields you saved on previous step:
    $shipping_address = $_POST['shipping_address'] ?? '';
    $shipping_city    = $_POST['shipping_city']    ?? '';
    $shipping_state   = $_POST['shipping_state']   ?? '';
    $shipping_zip     = $_POST['shipping_zip']     ?? '';
    $shipping_country = $_POST['shipping_country'] ?? '';
    $payment_method   = $_POST['payment_method']   ?? 'COD'; // or 'UPI', 'CARD'

    // 2) Start a transaction
    $conn->begin_transaction();

    try {
        // 3) Create Order (NOTE: no store_id here for multi-seller orders)
        $orderSql = "INSERT INTO Orders
            (user_id, status, shipping_address, shipping_city, shipping_state, shipping_zip, shipping_country, payment_method, payment_status)
            VALUES (?, 'Pending', ?, ?, ?, ?, ?, ?, 'Pending')";
        $stmt = $conn->prepare($orderSql);
        if (!$stmt) { throw new Exception("Prepare failed (order): " . $conn->error); }
        $stmt->bind_param(
            "issssss",
            $userId,
            $shipping_address,
            $shipping_city,
            $shipping_state,
            $shipping_zip,
            $shipping_country,
            $payment_method
        );
        if (!$stmt->execute()) { throw new Exception("Execute failed (order): " . $stmt->error); }
        $orderId = $stmt->insert_id;
        $stmt->close();

        // 4) Prepare statements for product fetch and item insert
        $prodStmt = $conn->prepare("SELECT product_id, product_name, price, store_id, quantity FROM Products WHERE product_id = ? FOR UPDATE");
        if (!$prodStmt) { throw new Exception("Prepare failed (product): " . $conn->error); }

        $itemStmt = $conn->prepare("INSERT INTO Order_Items
            (order_id, product_id, store_id, product_name, quantity, price, subtotal)
            VALUES (?, ?, ?, ?, ?, ?, ?)");
        if (!$itemStmt) { throw new Exception("Prepare failed (item): " . $conn->error); }

        $subtotal = 0.0;

        foreach ($cart as $line) {
            // READ FROM CART USING 'id' and 'qty'
            $pid = isset($line['id']) ? (int)$line['id'] : 0;
            $qty = isset($line['qty']) ? max(1, (int)$line['qty']) : 1;
            if ($pid <= 0) {
                throw new Exception("Cart item missing valid product id.");
            }

            // 5) Fetch the product (authoritative price, store, stock)
            $prodStmt->bind_param("i", $pid);
            if (!$prodStmt->execute()) { throw new Exception("Execute failed (product): " . $prodStmt->error); }
            $result = $prodStmt->get_result();
            $prod = $result->fetch_assoc();
            if (!$prod) {
                throw new Exception("Product not found (ID $pid).");
            }

            $pname   = $prod['product_name'];
            $price   = (float)$prod['price'];
            $storeId = (int)$prod['store_id'];
            $stock   = (int)$prod['quantity'];

            // (Optional) stock check
            if ($stock < $qty) {
                throw new Exception("Insufficient stock for '{$pname}'. Available: {$stock}");
            }

            $lineSubtotal = $price * $qty;
            $subtotal += $lineSubtotal;

            // 6) Insert order item (this is where product_id must NOT be null)
            $itemStmt->bind_param("iiisidd",
                $orderId,
                $pid,
                $storeId,
                $pname,
                $qty,
                $price,
                $lineSubtotal
            );
            if (!$itemStmt->execute()) {
                throw new Exception("Execute failed (order item): " . $itemStmt->error);
            }

            // (Optional) decrement stock
            $updateStock = $conn->prepare("UPDATE Products SET quantity = quantity - ? WHERE product_id = ?");
            $updateStock->bind_param("ii", $qty, $pid);
            if (!$updateStock->execute()) {
                throw new Exception("Stock update failed: " . $updateStock->error);
            }
            $updateStock->close();
        }

        $prodStmt->close();
        $itemStmt->close();

        // (Optional) if you later add totals/taxes columns to Orders, update here

        // 7) Commit, clear cart, go to success page
        $conn->commit();
        unset($_SESSION['cart']);
        header("Location: order_success.php?order_id=" . $orderId);
        exit;

    } catch (Exception $e) {
        $conn->rollback();
        // For production, log the error and show a friendly message
        die("Sorry, we couldn't place your order. Error: " . htmlspecialchars($e->getMessage()));
    }
?>