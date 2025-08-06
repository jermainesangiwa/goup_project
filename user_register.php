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
    $password = $_POST['password'];
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

<!-- User Registration -->
 <!DOCTYPE html>
 <html>
    <head>
        <meta charset="UTF-8">
        <meta name ="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Register Aisle24/7</title>
        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
        <!-- Dark mode styles -->
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
            .auth-container input[type="email"]{
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
            <h2><i class="fas fa-user-plus"></i> User Registration</h2>
            <form action="" method="POST">
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" name="first_name" placeholder="First Name" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-user"></i>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-phone"></i>
                    <input type="text" name="phone_number" placeholder="Phone Number" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="default_delivery_address" placeholder="Delivery Address" required>
                </div>
                <button type="submit"><i class="fas fa-user-plus"></i> Register</button>
            </form>
            <p>Already have an account? <a href="user_login.php">Login here</a></p>
            <p>Do you want to register as store owner? <a href="store_register.php">Register here</a></p>
        </div>
    </body>
 </html>