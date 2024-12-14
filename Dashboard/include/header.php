<?php function getHeader(string $currentPage)
{
    session_start();

    if (!isset($_SESSION['isAutharized'])) {
        echo "<script>window.location.href = '../AccessDenied/403.html'</script>";
        exit;
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <title><?= $currentPage ?></title>
        <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
        <link rel="icon" href="../assets/img/icon.ico" type="image/x-icon" />
        <script src="../js/jquery-3.6.4.min.js"></script>
        <script src="../js/sweetalert2.all.min.js"></script>
        <script src="../js/custome.js"></script>
        <script src="../assets/js/plugin/webfont/webfont.min.js"></script>
        <script>
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });
            WebFont.load({
                google: {
                    "families": ["Lato:300,400,700,900"]
                },
                custom: {
                    "families": ["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands",
                        "simple-line-icons"
                    ],
                    urls: ['../assets/css/fonts.min.css']
                },
                active: function() {
                    sessionStorage.fonts = true;
                }
            });
        </script>
        <link rel="stylesheet" href="../css/common.css">
        <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="../assets/css/atlantis.min.css">

        <link rel="stylesheet" href="../js/autocomplete/jquery-ui.structure.min.css">
        <link rel="stylesheet" href="../js/autocomplete/jquery-ui.min.css">
        <style>
            body {
                -webkit-user-select: none;
                -moz-user-select: none;
                -ms-user-select: none;
                user-select: none;
            }
        </style>
    </head>

    <body>
        <div class="wrapper">
            <div class="main-header">
                <div class="logo-header" data-background-color="blue">

                    <a href="dashboard" class="logo">
                        <img src="../assets/img/HypexLogo2.svg" alt="navbar brand" class="navbar-brand">
                    </a>
                    <button class="navbar-toggler sidenav-toggler ml-auto" type="button" data-toggle="collapse" data-target="collapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon">
                            <i class="icon-menu"></i>
                        </span>
                    </button>
                    <button class="topbar-toggler more"><i class="icon-options-vertical"></i></button>
                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="icon-menu"></i>
                        </button>
                    </div>
                </div>
                <nav class="navbar navbar-header navbar-expand-lg" data-background-color="blue2">

                    <div class="container-fluid">
                        <div class="collapse" id="search-nav">
                            <div class="navbar-left mr-md-3">
                                <h1 class="h1" style="color: white"><?= $currentPage ?></h1>
                            </div>
                        </div>

                    </div>
                </nav>
            </div>
            <div class="sidebar sidebar-style-2">
                <div class="sidebar-wrapper scrollbar scrollbar-inner">
                    <div class="sidebar-content">
                        <ul class="nav nav-primary">
                            <?php
                            ?>
                            <li class="nav-item <?= (stripos($currentPage, 'home') !== false) ? 'active' : ''; ?>" id="home">
                                <a data-toggle="collapse" href="dashboard" class="collapsed" aria-expanded="false">
                                    <i class="fas fa-home"></i>
                                    <p>Home</p>
                                </a>
                            </li>
                            <script>
                                $("#home").on("click", () => {
                                    window.location.href = "dashboard";
                                });
                            </script>
                            <li class="nav-section">
                                <span class="sidebar-mini-icon">
                                    <i class="fa fa-ellipsis-h"></i>
                                </span>
                            </li>
                            <?php $isCcategoriesPage = stripos($currentPage, 'categor') !== false; ?>
                            <li class="nav-item <?= $isCcategoriesPage ? "active submenu" : "" ?>">
                                <a data-toggle="collapse" href="#Categories" class=<?= $isCcategoriesPage ? "collapsed" : "" ?> aria-expanded="<?= boolval($isCcategoriesPage) ?>">
                                    <i class="fas fa-list-alt"></i>
                                    <p>Categories</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse <?= $isCcategoriesPage ? "show" : "" ?>" id="Categories">
                                    <ul class="nav nav-collapse">
                                        <li <?= strcasecmp($currentPage, "Show Categories") == 0 ? "class = 'active'" : "" ?>>
                                            <a href="categories">
                                                <span class="sub-item">Show Categories</span>
                                            </a>
                                        </li>
                                        <li class="submenu <?= strcasecmp($currentPage, "Create Category") == 0 ? "active" : '' ?>">
                                            <a href="categoriesCreation">
                                                <span class="sub-item">Create Categories</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            </li>
                            <?php
                            $isStoresPage = stripos($currentPage, 'Store') !== false;
                            ?>
                            <li class="nav-item <?= $isStoresPage ? "active collapsed" : "" ?>">
                                <a data-toggle="collapse" href="#Stores" class="<?= $isStoresPage ? "collapsed" : "" ?>" aria-expanded="<?= boolval($isStoresPage) ?>">
                                    <i class="fas fa-store"></i>
                                    <p>Stores</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse <?= $isStoresPage ? "show" : "" ?>" id="Stores">
                                    <ul class="nav nav-collapse">
                                        <li <?= strcasecmp($currentPage, "Show Stores") == 0 ? "class = 'active'" : "" ?>>
                                            <a href="stores">
                                                <span class="sub-item">Show Stores</span>
                                            </a>
                                        </li>
                                        <li <?= strcasecmp($currentPage, "Create Store") == 0 ? "class = 'active'" : "" ?>>
                                            <a href="storesCreation">
                                                <span class="sub-item">Create Store</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li class="nav-item <?= (stripos($currentPage, 'rating') !== false) ? 'active' : ''; ?>">
                                <a data-toggle="collapse" id="rating" href="rating">
                                    <i class="fas fa-star"></i>
                                    <p>Ratings</p>
                                </a>
                            </li>
                            <script>
                                $("#rating").on("click", () => {
                                    window.location.href = "rating";
                                });
                            </script>
                            <li class="nav-item">
                                <a data-toggle="collapse" href="/" id="logout">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <p>Logout</p>
                                </a>
                            </li>
                            <script>
                                $("#logout").on("click", () => {
                                    Swal.fire({
                                        title: 'Are you sure?',
                                        text: "You are going to logout!",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Yes, Logout!'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.href = "include/AdditionFiles/logout";
                                        }
                                    })
                                });
                            </script>
                        </ul>
                    </div>
                </div>
            </div>
        <?php }
