<?php
session_start();
include("config.php");

if (!isset($_SESSION['store_id'])) {
    header("Location: seller_login.php");
    exit();
}

$store_id = $_SESSION['store_id'];
$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match.";
    } else {
        // Fetch current hash
        $stmt = $conn->prepare("SELECT password_hash FROM Stores WHERE store_id = ?");
        $stmt->bind_param("i", $store_id);
        $stmt->execute();
        $stmt->bind_result($current_hash);
        $stmt->fetch();
        $stmt->close();

        if (password_verify($old_password, $current_hash)) {
            $new_hash = password_hash($new_password, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE Stores SET password_hash = ? WHERE store_id = ?");
            $update->bind_param("si", $new_hash, $store_id);
            $update->execute();
            $update->close();
            $success = "Password updated successfully!";
        } else {
            $error = "Old password is incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Change Password</title>
    <style>
        body { background:#121212; color:#fff; font-family:Arial; }
        .container { max-width:400px; margin:50px auto; padding:20px; background:#1e1e1e; border-radius:5px; }
        input { width:100%; padding:10px; margin:10px 0; border:1px solid #444; border-radius:5px; background:#2a2a2a; color:#fff; }
        button { padding:10px; width:100%; background:#4CAF50; border:none; border-radius:5px; color:#fff; cursor:pointer; }
        button:hover { background:#45a049; }
        .success { color:#4CAF50; text-align:center; }
        .error { color:#ff6b6b; text-align:center; }
    </style>
</head>
<body>
<div class="container">
    <h2>Change Password</h2>
    <?php if ($success) echo "<p class='success'>$success</p>"; ?>
    <?php if ($error) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="password" name="old_password" placeholder="Current Password" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Confirm Password" required>
        <button type="submit">Update Password</button>
    </form>
</div>
</body>
</html>