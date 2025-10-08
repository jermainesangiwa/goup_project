<?php
session_start();
include("config.php");

// Check if seller is logged in
if (!isset($_SESSION['store_id'])) {
    header("Location: store_login.php");
    exit();
}
$store_id = $_SESSION['store_id'];

// GET product ID
if(!isset($_GET['product_id']) || !ctype_digit($_GET['product_id'])) {
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
        FROM products 
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
        $update_sql = "UPDATE products 
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