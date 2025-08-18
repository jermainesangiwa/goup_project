<?php
session_start();
include("config.php");

if (isset($_SESSION['user_id'])) {
    header("Location: Homepage.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($input, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    }

    $stmt = $conn->prepare("SELECT user_id, email, password_hash FROM Users WHERE email = ?");
    $stmt->bind_param("s", $input);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($user_id, $email, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['email'] = $email;
            header("Location: Homepage.php");
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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - Aisle24/7</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('Untitled design (2).png') repeat;
            background-size: 400px auto; /* adjust tile size */
            animation: scrollUp 30s linear infinite;
            color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        @keyframes scrollUp {
            from { background-position: 0 0; }
            to { background-position: 0 -100%; }
        }

        .auth-container {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 8px;
            width: 350px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #ffffff;
        }

        .auth-container input[type="email"],
        .auth-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            background-color: #2a2a2a;
            color: #ffffff;
        }

        .auth-container button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        .auth-container button:hover {
            background-color: #45a049;
        }

        .auth-container p {
            text-align: center;
            margin-top: 15px;
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
            padding-left: 40px;
        }

        .input-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
        }

        .error-message {
            color: #ff6b6b;
            text-align: center;
            margin-bottom: 10px;
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
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-icon">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="user_register.php">Register here</a></p>
        <p>Login as store? <a href="store_login.php">Login as store here</a></p>
    </div>
</body>
</html>

