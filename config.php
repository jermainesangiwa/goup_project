<?php
// Database configuration
define('DB_HOST', 'sql306.infinityfree.com');
define('DB_USER', 'if0_39620755');
define('DB_PASS', 'Jermaine2000tz');
define('DB_NAME', 'if0_39620755_ailse247
');
define('DB_PORT', '3306'); // Assuming the port is 5432, adjust if necessary

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>