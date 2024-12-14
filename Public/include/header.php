<?php
include "../config/DB_Connection.php";
try {
    DB_Connection::newUniqueVisitor();
} catch (mysqli_sql_exception) {
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Hypex&trade; | Classified Marketplace</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Agency HTML Template">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="author" content="Yousef M. Y. AlSabbah">
    <meta name="generator" content="Hypex&trade; | Classified Marketplace Template">
    <link rel="stylesheet" href="../css/common.css">
    <link href="../assets/img/icon.ico" rel="shortcut icon">
    <link href="../plugins/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="../plugins/bootstrap/bootstrap-slider.css" rel="stylesheet">
    <link href="../plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="../plugins/slick/slick.css" rel="stylesheet">
    <link href="../plugins/slick/slick-theme.css" rel="stylesheet">
    <link href="../plugins/jquery-nice-select/css/nice-select.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <script>
        document.addEventListener('contextmenu', function(e) {
            e.preventDefault();
        });
    </script>
    <style>
        body {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</head>

<body class="body-wrapper" style="overflow-x: hidden;">


    <header>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <nav class="navbar navbar-expand-lg navbar-light navigation">
                        <a class="navbar-brand" href="/">
                            <img src="../assets/img/HypexLogo.svg" alt="">
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ml-auto main-nav ">
                                <li class="nav-item @@home">
                                    <a class="nav-link" href="/">Home</a>
                                </li>
                                <li class="nav-item dropdown dropdown-slide @@dashboard">
                                    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#!">Categories<span><i class="fa fa-angle-down"></i></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php
                                        require_once "../config/DB_Connection.php";
                                        $result = DB_Connection::getCategoriesASC();
                                        while ($row = $result->fetch_assoc()) {
                                        ?>
                                            <li><a class="dropdown-item" href="index?category_id=<?= $row["category_id"] ?>"><?= $row["Category_name"] ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            </ul>
                            <ul class="navbar-nav ml-auto mt-10">
                                <li class="nav-item" style="opacity: 0;">
                                    <a class="nav-link text-white add-button"><i class="fa fa-plus-circle"></i> Add Listing</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link login-button" href="login">Login</a>
                                </li>
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>