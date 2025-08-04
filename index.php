<?php
// 1️⃣ Immediately send a temporary redirect (HTTP 302) to user_login.php
header('Location: user_login.php');
exit;  // 2️⃣ Stop further execution so nothing else gets sent to the browser
?>