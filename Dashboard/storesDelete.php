<?php
session_start();

if (!isset($_SESSION['isAutharized'])) {
    echo "<script>window.location.href = '../AccessDenied/403.html'</script>";
    exit;
}
if (isset($_POST["id"])) {
    require_once "../config/DB_Connection.php";
    DB_Connection::deleteStore((int) $_POST["id"]);
    header("Location: stores.php?status=success&title=Deleted Successfully&msg=Store Deleted Successfully!");
    exit;
} else {
    die("You you're not allowed to get here!");
}
