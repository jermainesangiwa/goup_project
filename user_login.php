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
    $stmt = $conn->prepare("SELECT user_id, email, password_hash FROM Users WHERE email = ? AND password_hash = ?");
    $stmt->bind_param("ss", $input, password_hash($password, PASSWORD_BCRYPT));
    $stmt->execute();
    $stmt->store_result();

    // Check if user exists
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $email, $hashed_password);
        $stmt->fetch();

        // Verify password
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;

            // Redirect to home page
            header("Location: temp.html");
            exit();
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "User not found.";
    }
    $stmt->close();
}
?>

<!-- HTML for USER login form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - Ailse24/7</title>
    <!-- Font Awesome CDN -->
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
        <h2><i class="fas fa-sign-in-alt"></i> User Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="input-icon">
                <i class="fas fa-user"></i>
                <input type="text" name="email" placeholder="Email" required>
            </div>
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="user_register.php">Register here as user</a></p>
        <p>Login as store? <a href="store_login.php">Login here</a></p>
        <p>Register as store? <a href="store_register.php">Register here as store</a></p>
    </div>
</body>
</html>
