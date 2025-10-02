<?php
// Start the session
session_start();

// Connect to the database through config file
include('config.php');

// Seller must be logged in to access this page
if (!isset($_SESSION['store_id'])) {
    header('Location: store_login.php'); // Redirect to login page if not logged in
    exit;
}

// Get the store ID from the session
$storeId = $_SESSION['store_id'];

// Fetch seller Info
$storeSql = "SELECT * FROM Stores WHERE store_id = ?";
$stmt = $conn->prepare($storeSql);
$stmt->bind_param("i", $storeId);
$stmt->execute();
$result = $stmt->get_result();
$storeInfo = $result->fetch_assoc();

// Fetch products for the logged-in seller
$productSql = "SELECT * FROM Products WHERE store_id = ?";
$stmt = $conn->prepare($productSql);
$stmt->bind_param("i", $storeId);
$stmt->execute();
$products = $stmt->get_result();
?>

<!-- Seller Dashboard -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seller Dashboard - Aisle 24/7</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
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
        .dashboard {
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
        .sidebar a.active { background: #F9A41E; color: #000; font-weight: bold; }
        .content {
            flex: 1;
            padding: 24px;
            background: #fff;
        }
        h2 { margin-top: 0; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th { background: #eee; }
        .btn {
            display: inline-block;
            padding: 8px 16px;
            background: #F9A41E;
            color: #000;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 600;
        }
        .form-group { margin-bottom: 12px; }
        label { display: block; margin-bottom: 6px; font-weight: bold; }
        input, select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <h1>Aisle 24/7 - Seller Dashboard</h1>
        <div>
            Logged in as <strong><?php echo htmlspecialchars($storeInfo['store_name']); ?></strong>
            <a href="store_logout.php" class="btn" style="margin-left: 15px;">Logout</a>
        </div>
    </header>

    <!-- Dashboard Layout -->
    <div class="dashboard">
        <!-- Sidebar -->
        <nav class="sidebar">
            <h3>Menu</h3>
            <a href="?page=products" class="active">My Products</a>
            <a href="?page=add">Add Product</a>
            <a href="?page=orders">Orders</a>
        </nav>

        <!-- Main Content -->
        <main class="content">
            <?php
                // Determine which page to show based on 'page' parameter
                $page = $_GET['page'] ?? 'products';

                // Products List
                if ($page === 'products'){
                    // Display products table
                    echo "<h2>My Products</h2>";
                    echo "<table>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Image</th>
                            </tr>";
                    while ($row = $products->fetch_assoc()){
                        echo "<tr>
                                <td>".htmlspecialchars($row['product_id'])."</td>
                                <td>".htmlspecialchars($row['product_name'])."</td>
                                <td>".htmlspecialchars($row['category'])."</td>
                                <td>$".htmlspecialchars($row['price'])."</td>
                                <td><img src='{$row['product_image']}' alt='' width='60'></td>
                              </tr>";
                    }
                    echo "</table>";
                }

                // Add Product Form
                if ($page === 'add'){
                ?>
                    <h2>Add New Product</h2>
                    <form action="add_product.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" required>
                                <option value="Food">Food</option>
                                <option value="Fruit">Fruit</option>
                                <option value="Snack">Snack</option>
                                <option value="Drink">Drink</option>
                                <option value="Stationery">Stationery</option>
                                <option value="Essential">Essential</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" name="price" step="0.01" name="price" required>
                        </div>
                        <div class="form-group">
                            <label>Image Path</label>
                            <input type="text" name="product_image" placeholder="e.g. assets/food_rice.png" required>
                        </div>
                        <button type="submit" class="btn">Add Product</button>
                    </form>
                <?php
                }

                // Orders Page (Placeholder)
                if ($page === 'orders'){
                    echo "<h2>Orders</h2>";
                    echo "<p>Order management functionality coming soon!</p>";
                }
                ?>
        </main>
    </div>
</body>
</html>