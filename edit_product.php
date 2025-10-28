<?php
session_start();
include("config.php");

// Check if seller is logged in
if (!isset($_SESSION['store_id'])) {
    header("Location: store_login.php");
    exit();
}
$store_id = $_SESSION['store_id'];

// Validate and read product ID from the query string (?product_id=123)
$product_id = filter_input(INPUT_GET, 'product_id', FILTER_VALIDATE_INT, [
    'options' => ['min_range' => 1]
]);

if ($product_id === false || $product_id === null) {
    http_response_code(400);
    die("Invalid product ID.");
}

$product_id = $_GET['product_id'];

// Fetch products, ensure it belongs to the logged-in seller
$sql = "SELECT 
            product_id, 
            product_name, 
            category, 
            price, 
            product_image, 
            quantity,
            store_id 
        FROM Products 
        WHERE product_id = ? AND store_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $product_id, $store_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    http_response_code(404);
    die("Product not found or you do not have permission to edit this product.");
}

// Handle form submission
$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_name = trim($_POST['product_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = $_POST['price'] ?? '';
    $quantity = $_POST['quantity'] ?? '';
    $imagePath = trim($_POST['product_image'] ?? '');

    // Validate: name
    if ($product_name === '') {
        $errors[] = "Product name is required.";
    }

    // Validate: category (restrict to predefined categories)
    $allowed_categories = ["Food", "Fruit", "Snack", "Drink", "Stationery", "Essential"];
    if (!in_array($category, $allowed_categories, true)) {
        $errors[] = "Invalid category selected.";
    }

    // Validate: price (numeric, >= 0)
    if (!is_numeric($price) || $price < 0) {
        $errors[] = "Price must be a non-negative number.";
    } else {
        $price = (float)$price;
    }

    // Validate: image path (non-empty string, store path like assets/xxx.png)
    if ($imagePath === '' || !preg_match('/^assets\/[a-zA-Z0-9_\-]+\.(png|jpg|jpeg|gif)$/', $imagePath)) {
        $errors[] = "Invalid image path, image path is required";
    }

    // Validate: quantity (integer, >= 0)
    if (!ctype_digit((string)$quantity) || (int)$quantity < 0) {
        $errors[] = "Quantity must be a non-negative integer.";
    } else {
        $quantity = (int)$quantity;
    }

    // If no errors, update the product
    if (empty($errors)) {
        $update_sql = "UPDATE Products 
                       SET product_name = ?, category = ?, price = ?, product_image = ?, quantity = ? 
                       WHERE product_id = ? AND store_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ssdsiis", $product_name, $category, $price, $imagePath, $quantity, $product_id, $store_id);

        if ($update_stmt->execute()){
            $success = "Product updated successfully.";
            // Refresh product data so the form shows updated values
            $product['product_name'] = $product_name;
            $product['category'] = $category;
            $product['price'] = $price;
            $product['product_image'] = $imagePath;
            $product['quantity'] = $quantity;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
    }
}
?>

<!-- Edit Product HTML Form would go here -->
 <!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Edit Product – Aisle 24/7</title>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<style>
    /* --- Base (match seller dashboard) --- */
    body {
        font-family: 'Inter', sans-serif;
        margin: 0;
        background: #f9f9f9;
        color: #000;
    }
    header {
        background: rgba(0,0,0,0.8);
        padding: 15px 24px;
        color: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .wrap {
        display: flex;
        min-height: calc(100vh - 60px);
    }
    .sidebar {
        width: 220px;
        background: #252F3D;
        color: #fff;
        padding: 20px;
    }
    .sidebar h3 { margin-bottom: 20px; }
    .sidebar a {
        display: block;
        padding: 10px;
        margin-bottom: 8px;
        color: #fff;
        background: rgba(255,255,255,0.1);
        border-radius: 6px;
        text-decoration: none;
    }
    .sidebar a:hover { background: rgba(255,255,255,0.2); }
    .sidebar a.active { background: #F9A41E; color: #000; font-weight: bold; }

    .content {
        flex: 1;
        padding: 24px;
        background: #fff;
    }
    h2 { margin-top: 0; }

    /* --- Form styling --- */
    .card {
        background: #fff;
        border: 1px solid #e6e6e6;
        border-radius: 8px;
        padding: 20px;
        max-width: 720px;
    }
    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
    }
    label {
        font-weight: 600;
        margin-bottom: 6px;
    }
    input[type="text"],
    input[type="number"],
    select {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        background: #fff;
    }
    .btn-row {
        margin-top: 16px;
        display: flex;
        gap: 12px;
    }
    .btn {
        display: inline-block;
        padding: 10px 16px;
        background: #F9A41E;
        color: #000;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
        text-decoration: none;
    }
    .btn.secondary {
        background: #e6e6e6;
        color: #000;
    }
    .status {
        margin-bottom: 16px;
        padding: 10px 12px;
        border-radius: 6px;
        font-size: 14px;
    }
    .status.success { background: #E9F7EF; color: #1E7E34; border: 1px solid #CDEFD8; }
    .status.error   { background: #FDEDEC; color: #922B21; border: 1px solid #F5C6CB; }
    .preview {
        margin-top: 8px;
        display: inline-block;
        border: 1px solid #eee;
        border-radius: 6px;
        padding: 6px;
        background: #fafafa;
    }
    .preview img { max-height: 60px; display: block; }
    @media (max-width: 900px) {
        .grid { grid-template-columns: 1fr; }
        .sidebar { display: none; }
    }
</style>
</head>
<body>
<header>
    <h1>Aisle 24/7 – Edit Product</h1>
    <div>
        <a href="seller_dashboard.php" class="btn secondary">← Back to Dashboard</a>
    </div>
</header>

<div class="wrap">
    <!-- Sidebar (same style as dashboard) -->
    <nav class="sidebar">
        <h3>Menu</h3>
        <a href="seller_dashboard.php?page=products">My Products</a>
        <a href="seller_dashboard.php?page=add">Add Product</a>
        <a href="seller_dashboard.php?page=orders">Orders</a>
        <a href="reset_password.php">Change Password</a>
        <a href="store_logout.php">Logout</a>
    </nav>

    <!-- Main content -->
    <main class="content">
        <h2>Edit: #<?php echo htmlspecialchars($product['product_id']); ?></h2>

        <!-- Status messages -->
        <?php if (!empty($success)): ?>
            <div class="status success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>
        <?php if (!empty($errors)): ?>
            <div class="status error">
                <?php foreach ($errors as $e) { echo "• " . htmlspecialchars($e) . "<br>"; } ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <!--
                NOTE:
                - We keep the image as a path (e.g., assets/food_rice.png).
                - If you switch to file uploads later, replace this with <input type="file"> and handle the upload in PHP.
            -->
            <form method="POST">
                <div class="grid">
                    <div class="form-group">
                        <label for="product_name">Product Name</label>
                        <input
                            type="text"
                            id="product_name"
                            name="product_name"
                            value="<?php echo htmlspecialchars($product['product_name']); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="category">Category</label>
                        <select id="category" name="category" required>
                            <?php
                                foreach (["Food","Fruit","Snack","Drink","Stationary","Essential"] as $cat) {
                                    $sel = ($product['category'] === $cat) ? 'selected' : '';
                                    echo "<option value=\"{$cat}\" {$sel}>{$cat}</option>";
                                }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="price">Price (₹)</label>
                        <input
                            type="number"
                            id="price"
                            name="price"
                            step="0.01"
                            min="0"
                            value="<?php echo htmlspecialchars($product['price']); ?>"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="quantity">Quantity (stock)</label>
                        <input
                            type="number"
                            id="quantity"
                            name="quantity"
                            min="0"
                            value="<?php echo htmlspecialchars((string)$product['quantity']); ?>"
                            required
                        >
                    </div>

                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label for="product_image">Image Path</label>
                        <input
                            type="text"
                            id="product_image"
                            name="product_image"
                            placeholder="e.g. assets/food_ricebowl.png"
                            value="<?php echo htmlspecialchars($product['product_image']); ?>"
                            required
                        >
                        <?php if (!empty($product['product_image'])): ?>
                        <span class="preview">
                            <img src="<?= htmlspecialchars($line['img'] ?? 'assets/placeholder.png') ?>" alt="">
                            <h3 class="product-name"><?= htmlspecialchars($line['name'] ?? 'Item') ?></h3>
                            <span class="quantity"><?= (int)($line['qty'] ?? 1) ?></span>
                            <div class="product-price">₹<?= number_format(((float)($line['price'] ?? 0)) * ((int)($line['qty'] ?? 1)), 2) ?></div>
                        </span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="btn-row">
                    <button type="submit" class="btn">Save Changes</button>
                    <a href="seller_dashboard.php?page=products" class="btn secondary">Cancel</a>
                </div>
            </form>
        </div>
    </main>
</div>
</body>
</html>