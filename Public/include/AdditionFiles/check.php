<?php
session_start();
if (isset($_SESSION['Email'])) {
    extract($_SESSION);
    require_once "../../../config/DB_Connection.php";
    $result = DB_Connection::checkLoginInfo($Email, $Password);
    if ($result->num_rows > 0) {
        $Username = $result->fetch_array()[0];
        if (isset($_SESSION['keep'])) {
            setcookie("Username", $Username, time() + 3600, "/");
        }
        session_unset();
        session_destroy();
        session_start();
        $_SESSION['isAutharized'] = "TRUE";
        header("Location: ../../../Dashboard/dashboard?success=" . $Username);
    } else {
        header("Location: ../../login?printError=0");
    }
    exit;
} else {
    header("Location: ../../../AccessDenied/index");
    exit;
}
