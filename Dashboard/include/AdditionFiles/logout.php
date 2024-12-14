<?php
session_start();

if (!isset($_SESSION['isAutharized'])) {
    echo "<script>window.location.href = '../AccessDenied/403.html'</script>";
    exit;
}
session_start();
session_unset();
session_destroy();
if (isset($_COOKIE['Username'])) {
    setcookie("Username", "", time() - 3600, "/");
}

header("Location: /");
