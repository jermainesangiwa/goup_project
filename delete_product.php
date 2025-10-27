<?php
    session_start();
    include("config.php");

    // Require seller to be logged in
    if (!isset($_SESSION['store_id'])) {
        header("Location: store_login.php");
        exit();
    }

    $store_id = $_SESSION['store_id'];

    // Validate product ID
    if (!isset($_GET['product_id']) || !ctype_digit($_GET['product_id'])) {
    die("Invalid product ID.");
    }
    $product_id = (int)$_GET['product_id'];

    // Verify ownership
    $check = $conn->prepare("SELECT product_id FROM Products WHERE product_id = ? AND store_id = ?");
    $check->bind_param("ii", $product_id, $store_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        die("Error: You are not authorized to delete this product or it does not exist.");
    }

    // Perform deletion
    $delete = $conn->prepare("DELETE FROM Products WHERE product_id = ? AND store_id = ?");
    $delete->bind_param("ii", $product_id, $store_id);
    if ($delete->execute()) {
        // Redirect back with success message
        header("Location: seller_dashboard.php?page=products&msg=deleted");
        exit();
    } else {
        die("Error deleting product: " . $conn->error);
    }
?>