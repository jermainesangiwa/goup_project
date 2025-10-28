<?php
    // seller_dashboard.php
    session_start();
    include('config.php'); // your DB connection in $conn

    // --- Normalize session cart items to a consistent shape ---
    $_SESSION['cart'] = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];

    $normalized = [];
    foreach ($_SESSION['cart'] as $it) {
        $normalized[] = [
            'id'    => $it['id']    ?? ($it['product_id'] ?? null),
            'name'  => $it['name']  ?? ($it['product_name'] ?? 'Item'),
            'img'   => $it['img']   ?? ($it['product_image'] ?? 'assets/placeholder.png'),
            'price' => isset($it['price'])
                        ? (float)$it['price']
                        : (isset($it['unit_price']) ? (float)$it['unit_price'] : 0.0),
            'qty'   => isset($it['qty']) ? max(1, (int)$it['qty']) : 1,
        ];
    }
    $_SESSION['cart'] = $normalized;

    // Require seller login
    if (!isset($_SESSION['store_id'])) {
        header('Location: store_login.php');
        exit;
    }

    $storeId = (int) $_SESSION['store_id'];
    $messages = ['success' => [], 'error' => []];

    /*
    * Handle Add Product form POST (POST to same page)
    * Supports either entering an image path OR uploading a file.
    */
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_product') {
        // basic sanitization/validation
        $name = trim($_POST['product_name'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $price = $_POST['price'] ?? '';
        $quantity = $_POST['quantity'] ?? 0;
        $imagePath = trim($_POST['product_image'] ?? ''); // optional path field

        // Validate required fields
        if ($name === '') {
            $messages['error'][] = "Product name is required.";
        }
        if ($category === '') {
            $messages['error'][] = "Category is required.";
        }
        if ($price === '' || !is_numeric($price) || $price < 0) {
            $messages['error'][] = "Provide a valid price >= 0.";
        }
        if (!is_numeric($quantity) || $quantity < 0) {
            $messages['error'][] = "Quantity must be a non-negative integer.";
        }

        // Handle image upload if present
        if (isset($_FILES['product_image_file']) && $_FILES['product_image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['product_image_file'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                // validate file type (basic)
                $allowed = ['image/png','image/jpeg','image/webp','image/gif'];
                $finfo = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($finfo, $file['tmp_name']);
                finfo_close($finfo);

                if (!in_array($mime, $allowed)) {
                    $messages['error'][] = "Uploaded image must be PNG, JPG, WEBP or GIF.";
                } else {
                    // create unique filename and move
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $safeName = preg_replace('/[^a-z0-9_\-\.]/i', '_', pathinfo($file['name'], PATHINFO_FILENAME));
                    $targetFilename = 'assets/' . $safeName . '_' . time() . '.' . $ext;
                    if (!is_dir('assets')) {
                        mkdir('assets', 0755, true);
                    }
                    if (!move_uploaded_file($file['tmp_name'], $targetFilename)) {
                        $messages['error'][] = "Failed to move uploaded file. Check permissions on the assets folder.";
                    } else {
                        $imagePath = $targetFilename;
                    }
                }
            } else {
                $messages['error'][] = "Error uploading image (code {$file['error']}).";
            }
        }

        // If no upload and no typed path, set default placeholder
        if (empty($imagePath)) {
            $imagePath = 'assets/placeholder.png'; // ensure placeholder exists
        }

        // If still no validation errors, insert product
        if (empty($messages['error'])) {
            $insertSql = "INSERT INTO Products (store_id, product_name, category, price, product_image, quantity, created_at)
                        VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($insertSql);
            if (!$stmt) {
                $messages['error'][] = "DB prepare failed: " . $conn->error;
            } else {
                $priceFloat = (float)$price;
                $qtyInt = (int)$quantity;
                $stmt->bind_param("issdis", $storeId, $name, $category, $priceFloat, $imagePath, $qtyInt);
                if ($stmt->execute()) {
                    $messages['success'][] = "Product '{$name}' added successfully.";
                } else {
                    $messages['error'][] = "DB insert failed: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }

    // Fetch store info (fresh)
    $storeSql = "SELECT store_id, store_name, email FROM Stores WHERE store_id = ?";
    $storeStmt = $conn->prepare($storeSql);
    $storeStmt->bind_param("i", $storeId);
    $storeStmt->execute();
    $storeResult = $storeStmt->get_result();
    $storeInfo = $storeResult->fetch_assoc();
    $storeStmt->close();

    // Count of Orders for this seller
    $newOrdersSql = "SELECT COUNT(DISTINCT o.order_id) AS order_count
                     FROM Orders o
                     JOIN Order_Items oi ON o.order_id = oi.order_id
                     WHERE oi.store_id = ?
                        AND o.payment_status IN ('Pending', 'Paid', 'Refunded')";
    $newOrderStmt = $conn->prepare($newOrdersSql);
    $newOrderStmt->bind_param("i", $storeId);
    $newOrderStmt->execute();
    $newOrderResult = $newOrderStmt->get_result();
    $newOrdersCount = $newOrderResult->fetch_assoc();
    $newOrdersCount = (int)($newOrdersCount['order_count'] ?? 0);
    $newOrderStmt->close();

    // Fetch products for this seller
    $productSql = "SELECT product_id, product_name, category, price, product_image, quantity FROM Products WHERE store_id = ? ORDER BY product_id DESC";
    $pstmt = $conn->prepare($productSql);
    $pstmt->bind_param("i", $storeId);
    $pstmt->execute();
    $products = $pstmt->get_result();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Seller Dashboard - Aisle 24/7</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        :root{
            --accent:#F9A41E;
            --dark:#252F3D;
            --muted:#f3f3f3;
        }
        body{font-family:Inter,system-ui,Arial,sans-serif;margin:0;background:var(--muted);color:#111;}
        header{background:rgba(0,0,0,0.8);padding:14px 24px;color:#fff;display:flex;justify-content:space-between;align-items:center}
        .dashboard{display:flex;min-height:calc(100vh - 60px)}
        .sidebar{width:240px;background:var(--dark);color:#fff;padding:20px}
        .sidebar h3{margin:0 0 14px}
        .sidebar a{display:block;padding:10px;border-radius:6px;color:#fff;text-decoration:none;margin-bottom:8px;background:rgba(255,255,255,0.04)}
        .sidebar a.active{background:var(--accent);color:#000;font-weight:700}
        .badge{display:inline-block;min-width:20px;padding:2px 6px;font-size:12px;line-height:1;border-radius:999px;background:#ff4d4f;color:#fff;text-align:center;vertical-align:middle;margin-left:8px;font-weight:700;}
        .content{flex:1;padding:28px;background:#fff}
        h2{margin-top:0}
        table{width:100%;border-collapse:collapse;margin-bottom:18px}
        th,td{border:1px solid #e8e8e8;padding:10px;text-align:left;vertical-align:middle}
        th{background:#fafafa}
        img.thumb{width:60px;height:60px;object-fit:cover;border-radius:6px}
        .btn{display:inline-block;padding:8px 14px;background:var(--accent);color:#000;border-radius:6px;text-decoration:none;font-weight:700}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .form-group{margin:10px 0}
        label{display:block;font-weight:600;margin-bottom:6px}
        input[type="text"], input[type="number"], select{width:100%;padding:8px;border:1px solid #ccc;border-radius:6px}
        input[type="file"]{padding:6px}
        .messages{margin:10px 0}
        .messages .success{color:green}
        .messages .error{color:#ff4d4f}
        @media(max-width:900px){
            .form-row{grid-template-columns:1fr}
            .sidebar{display:none}
        }
    </style>
</head>
<body>
<header>
    <div><strong>Aisle 24/7</strong> - Seller Dashboard</div>
    <div>
        Logged in as <strong><?php echo htmlspecialchars($storeInfo['store_name'] ?? 'Store'); ?></strong>
        <a href="seller_logout.php" class="btn" style="margin-left:14px">Logout</a>
    </div>
</header>

<div class="dashboard">
    <nav class="sidebar">
        <h3>Menu</h3>
        <a href="?page=products" class="active">My Products</a>
        <a href="?page=add">Add Product</a>
        <a href="?page=orders">
            Orders
            <?php if ($newOrdersCount > 0): ?>
                <span class="badge"><?php echo $newOrdersCount; ?></span>
            <?php endif; ?>
        </a>
        <a href="reset_password.php">Change Password</a>
        <a href="seller_logout.php">Logout</a>
    </nav>

    <main class="content">
        <!-- Toast/status when there are new orders -->
         <?php if (!isset($_GET['page']) || $_GET['page'] === 'products'): ?>
            <?php if ($newOrdersCount > 0): ?>
                <div class="messages">
                    <div class="success">You have <?php echo $newOrdersCount; ?> new order(s) awaiting review in the <a href="?page=orders">Orders</a> tab.</div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- show deletion message if applicable -->
        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="status success">Product deleted successfully.</div>
        <?php endif; ?>

        <?php
        // show messages
        if (!empty($messages['success']) || !empty($messages['error'])) {
            echo '<div class="messages">';
            foreach ($messages['success'] as $s) {
                echo "<div class='success'>✔ " . htmlspecialchars($s) . "</div>";
            }
            foreach ($messages['error'] as $e) {
                echo "<div class='error'>✖ " . htmlspecialchars($e) . "</div>";
            }
            echo '</div>';
        }

        $page = $_GET['page'] ?? 'products';

        if ($page === 'products'):
        ?>
            <h2>My Products</h2>
            <p><a href="?page=add" class="btn">+ Add new product</a></p>
            <table>
                <thead>
                    <tr><th>ID</th><th>Image</th><th>Name</th><th>Category</th><th>Price</th><th>Qty</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php while ($row = $products->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo (int)$row['product_id']; ?></td>
                            <td>
                                <?php $img = htmlspecialchars($row['product_image'] ?: 'assets/placeholder.png'); ?>
                                <img src="<?php echo $img; ?>" alt="" class="thumb">
                            </td>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td>₹<?php echo number_format((float)$row['price'], 2); ?></td>
                            <td><?php echo (int)$row['quantity']; ?></td>
                            <td>
                                <!-- placeholders for edit/delete features -->
                                <a class="btn" href="edit_product.php?product_id=<?= (int)$row['product_id'] ?>">Edit</a>
                                <a href="delete_product.php?id=<?php echo (int)$row['product_id']; ?>"
                                    class="btn" 
                                    style="background:#ff4d4f;color:#fff;margin-left:8px"
                                    onclick="return confirm('Are you sure you want to delete this product?');">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php elseif ($page === 'add'): ?>
            <h2>Add New Product</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="add_product">
                <div class="form-group">
                    <label>Product name</label>
                    <input type="text" name="product_name" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category" required>
                            <option value="">-- choose --</option>
                            <option value="Food">Food</option>
                            <option value="Fruit">Fruit</option>
                            <option value="Snack">Snack</option>
                            <option value="Drink">Drink</option>
                            <option value="Stationery">Stationery</option>
                            <option value="Essential">Essential</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Price (₹ / currency)</label>
                        <input type="number" step="0.01" name="price" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Quantity</label>
                        <input type="number" name="quantity" value="1" min="0" required>
                    </div>

                    <div class="form-group">
                        <label>Image path (optional)</label>
                        <input type="text" name="product_image" placeholder="e.g. assets/product_x.png">
                    </div>
                </div>

                <div class="form-group">
                    <label>Or upload image (optional)</label>
                    <input type="file" name="product_image_file" accept="image/*">
                </div>

                <div style="margin-top:12px">
                    <button type="submit" class="btn">Add Product</button>
                    <a href="?page=products" class="btn" style="background:#eee;color:#000;margin-left:8px">Cancel</a>
                </div>
            </form>

        <?php elseif ($page === 'orders'): ?>
            <?php
                // Mark all items in orders for this seller as seen when the Orders tab is opened
                $markSeenSql = "
                    UPDATE Order_Items oi
                    JOIN Products p ON p.product_id = oi.product_id
                    JOIN Orders   o ON o.order_id = oi.order_id
                    SET oi.seller_seen = 1
                    WHERE p.store_id = ?
                    AND o.payment_status IN ('Pending','Paid','Refunded')
                    AND (oi.seller_seen IS NULL OR oi.seller_seen = 0)
                ";
                $markSeenStmt = $conn->prepare($markSeenSql);
                $markSeenStmt->bind_param("i", $storeId);
                $markSeenStmt->execute();
                $markSeenStmt->close();

                // Fetch orders for this seller
                $ordersSql = "
                    SELECT 
                        o.order_id,
                        o.order_date,
                        o.status,
                        o.payment_status,
                        SUM(oi.quantity * oi.price) AS total,
                        GROUP_CONCAT(CONCAT(oi.product_name,' ×', oi.quantity) ORDER BY oi.order_item_id SEPARATOR ', ') AS items
                    FROM Orders o
                    JOIN Order_Items oi ON oi.order_id = o.order_id
                    JOIN Products p     ON p.product_id = oi.product_id
                    WHERE p.store_id = ?
                    GROUP BY o.order_id, o.order_date, o.status, o.payment_status
                    ORDER BY o.order_id DESC
                ";
                $ordersStmt = $conn->prepare($ordersSql);
                $ordersStmt->bind_param("i", $storeId);
                $ordersStmt->execute();
                $orders = $ordersStmt->get_result();
            ?>
            <h2>Orders</h2>
            <table>
            <thead>
                <tr>
                <th>Order #</th>
                <th>Date</th>
                <th>Items</th>
                <th>Status</th>
                <th>Payment</th>
                <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($orders->num_rows === 0): ?>
                <tr><td colspan="6">No orders yet.</td></tr>
                <?php else: ?>
                <?php while ($row = $orders->fetch_assoc()): ?>
                    <tr>
                    <td><?= (int)$row['order_id'] ?></td>
                    <td><?= htmlspecialchars($row['order_date']) ?></td>
                    <td><?= htmlspecialchars($row['items']) ?></td>
                    <td><?= htmlspecialchars($row['status']) ?></td>
                    <td><?= htmlspecialchars($row['payment_status']) ?></td>
                    <td>₹<?= number_format((float)$row['total'], 2) ?></td>
                    </tr>
                <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
            </table>
        <?php else: ?>
            <h2>Dashboard</h2>
            <p>Select an item from the menu.</p>
        <?php endif; ?>
    </main>
</div>
</body>
</html>