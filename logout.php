<?php
session_start(); // Start session to manage user login state

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: grocery food&fruits.php");
exit();
?>