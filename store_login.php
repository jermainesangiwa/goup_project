<?php
// Session start for managing store login state
session_start();

// Include database configuration
include("config.php");

// Redirect to store home page if already logged in
if (isset($_SESSION['store_id'])) {
    header("Location: seller dashboard.php");
    exit();
}

// Initialize error message
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = trim($_POST['email']);
    $password = $_POST['password'];

    // Validate email format
    if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }

    // Match email
    $stmt = $conn->prepare("SELECT store_id, email, password_hash FROM Stores WHERE email = ?");
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $stmt->store_result();

    // Check if store exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($store_id, $email, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables
            $_SESSION['store_id'] = $store_id;
            $_SESSION['email'] = $email;

            // Redirect to store home page
            header("Location: seller dashboard.php");
            exit();
        } else {
            $error = "Invalid email or password."; // Incorrect password
        }
    } else {
        $error = "Store not found."; // No store with that email
    }
}
?>

<!-- HTML for STORE login form -->
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Store Login - Aisle24/7</title>
        <!-- Font Awesome for icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <!-- Internal CSS for styling -->
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
            .auth-container input[type="email"],
            .auth-container input[type="password"] {
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
            <h2><i class="fas fa-store"></i> Store Login</h2>
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="input-icon">
                    <i class="fas fa-envelope"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-icon">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="store_register.php">Register here</a></p>
            <p>Login as user? <a href="user_login.php">Login here</a></p>
        </div>
    </body>
</html>