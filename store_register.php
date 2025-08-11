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
    $checkStmt->close();

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Prepare statement to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO Stores (store_name, owner_name, address, phone_number, email, password_hash, opening_time, closing_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $storeName, $ownerName, $storeAddress, $storePhone, $storeEmail, $hashedPassword, $openingTime, $closingTime);

    // Execute and redirect
    if ($stmt->execute()) {
        header("Location: store_login.php");
    } else {
        echo "Error: " . $stmt->error;
    }

}
?>

<!-- Store Registration -->
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Store Registration - Ailse 24/7</title>
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <style>
            body {
                background-color: #121212;
                color: #ffffff;
                font-family: Arial, sans-serif;
            }
            /* Use boder-box globally */
            *, *:before, *:after {
                box-sizing: border-box;
            }
            .auth-container {
                max-width: 400px;
                margin: 50px auto;
                padding: 20px;
                border: 1px solid #333;
                border-radius: 5px;
                background-color: #1e1e1e;
            }
            h2 {
                text-align: center;
                color: #ffffff;
            }
            .auth-container input[type="text"],
            .auth-container input[type="password"],
            .auth-container input[type="email"],
            .auth-container select {
                width: 100%;
                padding: 10px;
                margin: 10px 0;
                border: 1px solid #333;
                border-radius: 5px;
                background-color: #2a2a2a;
                color: #ffffff;
            }
            .auth-container button {
                width: 100%;
                padding: 10px;
                background-color: #4CAF50;
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            .auth-container button:hover {
                background-color: #45a049;
            }
            .auth-container p {
                text-align: center;
                color: #ffffff;
            }
            .auth-container p a {
                color: #4CAF50;
            }
            .auth-container p a:hover {
                color: #45a049;
            }
            .input-icon {
                position: relative;
            }
            .input-icon input {
                width: calc(100% - 40px); /* Adjust width to account for icon */
                margin: 0 20px auto; /* Center the input */
                padding-left: 40px; /* Prevents text overlap with icon */
            }
            .input-icon i {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                width: 20px; /* Limit icon width */
                pointer-events: none; /* Prevents icon from capturing clicks */
                color: #888;
            }
            .error-message {
                color: #ff6b6b;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="auth-container">
            <h2>Store Registration</h2>
            <form action="store_register.php" method="POST">
                <div class="input-icon">
                    <i class="fas fa-store"></i>
                    <input type="text" name="store_name" placeholder="Store Name" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" name="owner_name" placeholder="Owner Name" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="store_address" placeholder="Store Address" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="store_phone" placeholder="Phone Number" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="store_email" placeholder="Email" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-door-open"></i>
                    <select name="Is Open" required>
                        <option value="">Is Open</option>
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="input-icon">
                    <i class="fas fa-clock"></i>
                    <input type="text" name="opening_time" placeholder="Opening Time" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-clock"></i>
                    <input type="text" name="closing_time" placeholder="Closing Time" required>
                </div>
                <button type="submit">Register</button>
            </form>
            <p>Already have an account? <a href="store_login.php">Login here</a></p>
        </div>
    </body>
</html>