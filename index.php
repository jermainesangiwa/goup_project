<?php
// 1️⃣ Immediately send a temporary redirect (HTTP 302) to grocery-main.php
header('Location: grocery food.php');
exit;  // 2️⃣ Stop further execution so nothing else gets sent to the browser
?>