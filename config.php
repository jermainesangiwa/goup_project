<?php
// Database configuration
define('DB_HOST', 'jdrive0910-sangiwa-63af.h.aivencloud.com');
define('DB_USER', 'avnadmin');
define('DB_PASS', 'AVNS_Em3M-Zft657W02bs9p3');
define('DB_PORT', '26925'); // Assuming the port is 5432, adjust if necessary

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>