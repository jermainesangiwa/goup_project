<?php
// Database configuration, using config.php
include("config.php");

// Take user to home page if already logged in
if (isset($_SESSION['user_id'])) 
{
    header("Location: home.html");
    exit();
}

// Initialize error message
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = trim($_POST['email']);
    $password = $_POST['password'];

    // Match email
    $stmt = $conn->prepare("SELECT user_id, email, password_hash FROM Users WHERE email = ?");
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $stmt->store_result();
}

?>