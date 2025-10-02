<?php
// rehash_store_passwords.php
session_start();
require 'config.php';

// Map the known seller emails to their intended plaintext passwords
$updates = [
    'food@aisle247.com'         => 'foodpass',
    'fruits@aisle247.com'       => 'fruitspass',
    'snacksdrinks@aisle247.com' => 'snackdrinks',
    'stationary@aisle247.com'   => 'stationarypass',
    'essentials@aisle247.com'   => 'essentialspass',
];

$ok = 0; $miss = 0; $fail = 0;

foreach ($updates as $email => $plain) {
    // Confirm the store exists
    $check = $conn->prepare("SELECT store_id FROM Stores WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows === 0) {
        echo "❌ No store found for {$email}<br>";
        $miss++;
        $check->close();
        continue;
    }
    $check->bind_result($store_id);
    $check->fetch();
    $check->close();

    // Hash the password and update
    $hash = password_hash($plain, PASSWORD_DEFAULT);
    if ($hash === false) {
        echo "❌ Could not hash for {$email}<br>";
        $fail++;
        continue;
    }

    $upd = $conn->prepare("UPDATE Stores SET password_hash = ? WHERE email = ?");
    $upd->bind_param("ss", $hash, $email);
    if ($upd->execute()) {
        echo "✅ Updated password for {$email} (store_id {$store_id})<br>";
        $ok++;
    } else {
        echo "❌ Update failed for {$email}: " . $conn->error . "<br>";
        $fail++;
    }
    $upd->close();
}

echo "<hr>Done. Success: {$ok}, Missing: {$miss}, Failed: {$fail}<br>";
echo "Now try logging in via seller_login.php, then DELETE this file for security.";

$conn->close();
