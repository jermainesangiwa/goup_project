<?php
// Start session to manage store registration
session_start();

// Database connection using config.php
include("config.php");

// Store registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $storeName = trim($_POST['store_name']);
    $ownerName = trim($_POST['owner_name']);
    $storeAddress = trim($_POST['store_address']);
    $storePhone = trim($_POST['store_phone']);
    $storeEmail = trim($_POST['store_email']);
    $password = $_POST['password'];
    $openingTime = $_POST['opening_time'];
    $closingTime = $_POST['closing_time'];

    // Check if Email already exists
    $checkStmt = $conn->prepare("SELECT email FROM Stores WHERE email = ?");
    $checkStmt->bind_param("s", $storeEmail);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>alert('Email already exists. Please use a different email.');</script>";
        $checkStmt->close();
        exit();
    }

}
?>