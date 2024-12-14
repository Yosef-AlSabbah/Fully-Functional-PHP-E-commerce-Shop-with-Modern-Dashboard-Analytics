<?php
session_start();

if (!isset($_SESSION['isAutharized'])) {
    echo "<script>window.location.href = '../AccessDenied/403.html'</script>";
    exit;
}
if (isset($_POST["id"])) {
    require_once "../config/DB_Connection.php";
    try {
        DB_Connection::deleteCategory($_POST["id"]);
        header("Location: categories.php?status=success&title=Deleted Successfully&msg=Category Deleted Successfully!");
    } catch (mysqli_sql_exception) {
        header("Location: categories.php?status=error&title=Deletion Failed&msg=One or more Stores are associated with this Category!");
    } finally {
        exit;
    }
} else {
    die("You you're not allowed to get here!");
}
