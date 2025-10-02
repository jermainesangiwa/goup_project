<?php
    // seller_dashboard.php
    session_start();
    include('config.php'); // your DB connection in $conn

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
        <a href="store_logout.php" class="btn" style="margin-left:14px">Logout</a>
    </div>
</header>

<div class="dashboard">
    <nav class="sidebar">
        <h3>Menu</h3>
        <a href="?page=products" class="active">My Products</a>
        <a href="?page=add">Add Product</a>
        <a href="?page=orders">Orders</a>
        <a href="reset_password.php">Change Password</a>
        <a href="seller_logout.php">Logout</a>
    </nav>

    <main class="content">
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
                            <td>$<?php echo number_format((float)$row['price'], 2); ?></td>
                            <td><?php echo (int)$row['quantity']; ?></td>
                            <td>
                                <!-- placeholders for edit/delete features -->
                                <a href="edit_product.php?id=<?php echo (int)$row['product_id']; ?>" class="btn" style="background:#eee;color:#000">Edit</a>
                                <a href="delete_product.php?id=<?php echo (int)$row['product_id']; ?>" class="btn" style="background:#f66;color:#fff" onclick="return confirm('Delete this product?')">Delete</a>
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
            <h2>Orders</h2>
            <p>Order tracking and management will go here (you can show orders that include products with this store_id).</p>
            <p>Suggested: show order id, customer name, items, total, status, and actions (mark shipped, cancel).</p>

        <?php else: ?>
            <h2>Dashboard</h2>
            <p>Select an item from the menu.</p>
        <?php endif; ?>
    </main>
</div>
</body>
</html>