<?php
// Database connection using config.php
include("config.php");

// User Registration
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get and sanitize form data
    $firstname = trim($_POST['first_name']);
    $lastname = trim($_POST['last_name']);
    $gender = trim($_POST['gender']); // gender
    $email = trim($_POST['email']);
    $password = $_POST['password_hash'];
    $phoneNumber = trim($_POST['phone_number']);
    $deliveryAddress = trim($_POST['default_delivery_address']);

    // Prepare statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO Users (first_name, last_name, email, password_hash, phone_number, default_delivery_address) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $firstname, $lastname, $email, password_hash($password, PASSWORD_BCRYPT), $phoneNumber, $deliveryAddress);

    // Execute and redirect
    if ($stmt->execute()) {
        header("Location: user_login.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

?>