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
    <title>Aisle24/7 - Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('grocery-bg.jpg') no-repeat center center/cover;
        }
        .overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.55);
            backdrop-filter: blur(4px);
        }
        .container {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #fff;
        }
        .brand {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        .login-box {
            background: rgba(255,255,255,0.1);
            padding: 30px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .login-box h2 {
            font-weight: 600;
            margin-bottom: 20px;
        }
        .input-group {
            position: relative;
            margin-bottom: 15px;
        }
        .input-group i {
            position: absolute;
            top: 50%;
            left: 12px;
            transform: translateY(-50%);
            color: #ccc;
        }
        .input-group input {
            width: 100%;
            padding: 12px 12px 12px 40px;
            border: none;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            background: rgba(255,255,255,0.2);
            color: #fff;
        }
        .input-group input::placeholder {
            color: #ddd;
        }
        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background: rgba(255,255,255,0.2);
            color: #fff;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn:hover {
            background: rgba(255,255,255,0.4);
        }
        .links {
            margin-top: 10px;
            font-size: 13px;
        }
        .links a {
            color: #fff;
            text-decoration: underline;
        }
        .remember {
            text-align: left;
            margin: 10px 0;
            font-size: 13px;
        }
        .error-message {
            background: rgba(255, 0, 0, 0.2);
            color: #ff6b6b;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <div class="container">
        <div class="brand">AISLE24/7</div>
        <div class="login-box">
            <h2>Login</h2>
            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="remember">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember Me</label>
                </div>
                <button type="submit" class="btn">Sign In</button>
            </form>
            <div class="links">
                <p>Don't have an account? <a href="user_register.php">Sign Up</a></p>
                <p><a href="forgot_password.php">Forgot password?</a></p>
                <p><a href="store_login.php">Login as store</a></p>
            </div>
        </div>
    </div>
</body>
</html>
