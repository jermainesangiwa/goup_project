<?php
session_start();
include('config.php');


if(!isset($_GET['product_id']) || !ctype_digit($_GET['product_id'])) {
    exit('Invalid Product');
}
$product_id = (int)$_GET['product_id'];

$sql = "SELECT product_id, product_name, price, product_image, store_id FROM Products WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$p = $result->fetch_assoc();

if(!$p) {
    exit('Product not found');
}

if(!isset($_SESSION['cart'])) $_SESSION['cart'] = [];

$found = false;
foreach($_SESSION['cart'] as &$item) {
    if($item['product_id'] === (int)$p['product_id']){
        $item['quantity']++;
        $found = true;
        break;
    }
}
unset($item);

if(!$found) {
    $_SESSION['cart'][] = [
        'product_id' => (int)$p['product_id'],
        'store_id' => (int)$p['store_id'],
        'product_name' => $p['product_name'],
        'price' => (float)$p['price'],
        'product_image' => $p['product_image'],
        'quantity' => 1
    ];
}

header("Location: cart.php");
exit;
?>