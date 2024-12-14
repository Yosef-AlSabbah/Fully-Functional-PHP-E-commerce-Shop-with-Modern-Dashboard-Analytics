<link rel="stylesheet" href="../assets/css/atlantis.min.css">
<?php
session_start();
if (isset($_COOKIE['Username'])) {
  $_SESSION['isAutharized'] = "TRUE";
  header("Location: ../Dashboard/dashboard?success=" . $_COOKIE['Username']);
  exit;
}
if (isset($_SESSION['isAutharized'])) {
  header("Location: ../Dashboard/dashboard");
}
include_once "include/header.php";
?>
<script src="../js/jquery-3.6.4.min.js"></script>
<script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<style>
  .main-nav .nav-item .nav-link {
    font-size: 16px !important;
    line-height: 43px !important;
    padding: 0 20px !important;
  }

  .navbar-light .navbar-nav .nav-link {
    color: rgba(0, 0, 0, .5) !important;
  }

  .footer {
    border-top: none !important;
  }

  .border,
  .bg-gray.p-4 {
    border-radius: 15px;
  }

  .bg-gray.p-4 {
    border-bottom-left-radius: 0px;
    border-bottom-right-radius: 0px;
  }

  .nav-link.login-button {
    display: none !important;
  }

  .form-control {
    height: 45px !important;
    border-radius: 5px;
  }

  .pt-5,
  .py-5 {
    padding-top: 3rem !important;
    padding-bottom: 5rem !important;
  }
</style>
<script>
  function somethingWentWrong(title, msg) {
    var content = {};
    content.message = msg;
    content.title = title;
    content.icon = 'fa fa-bell';
    $.notify(content, {
      type: "danger",
      placement: {
        from: "top",
        align: "center"
      },
      time: 100,
      delay: 0,
    });
  };
</script>
<?php
$Email = "";
$Password = "";
$printError = false;
if (isset($_POST["submitBtn"])) {
  require_once "include/AdditionFiles/LoginFormValidation.php";
  $validator = new LoginValidation($_POST);
  $errors = $validator->validate();
  if (empty($errors)) {
    $_SESSION['Email'] = $_POST['Email'];
    $_SESSION['Password'] = $_POST['Password'];
    if (isset($_POST['keepMeLoggedIn'])) {
      $_SESSION['keep'] = TRUE;
    }
    echo '<script>window.location.href = "include/AdditionFiles/check";</script>';
    exit;
  } else {
    $printError = true;
  }
}
if (!empty($_POST)) {
  $Email = htmlspecialchars($_POST['Email']);
  $Password = htmlspecialchars($_POST['Password']);
} else if (!empty($_SESSION['Email'])) {
  $Email = htmlspecialchars($_SESSION['Email']);
  $Password = htmlspecialchars($_SESSION['Password']);
  session_unset();
  session_destroy();
}
if (isset($_GET['printError']) || $printError) {
  echo "<script>somethingWentWrong('Failed to login', 'Failed to login, Check Email or Password!');</script>";
}
?>
<style>
  .error {
    color: darkred !important;
  }
</style>
<section class="login py-5 border-top-1">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-8 align-item-center">
        <div class="border">
          <h3 class="bg-gray p-4">Login Now</h3>
          <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
            <fieldset class="p-4">
              <div class="mb-3">
                <input class="form-control" type="email" name="Email" placeholder="Email" value="<?= htmlspecialchars($Email) ?>" required>
                <?php if (isset($errors['Email'])) { ?>
                  <small id="EmailCheck" class="form-text text-muted error"> <?= htmlspecialchars($errors['Email']) ?></small>
                <?php } ?>
              </div>
              <div class="mb-3">
                <input class="form-control" type="Password" name="Password" placeholder="Password" value="<?= htmlspecialchars($Password) ?>" required>
                <?php if (isset($errors['Password'])) { ?>
                  <small id="passwordCheck" class="form-text text-muted error"> <?= htmlspecialchars($errors['Password']) ?></small>
                <?php } ?>
              </div>
              <div class="loggedin-forgot">
                <input type="checkbox" id="keep-me-logged-in" name="keepMeLoggedIn" value="keep">
                <label for="keep-me-logged-in" class="pt-3 pb-2">Keep me logged in</label>
              </div>
              <button type="submit" class="btn btn-primary font-weight-bold mt-3" name="submitBtn" value="submitBtn">Log in</button>
              <a class="mt-3 d-block text-primary">Forget Password?</a>
              <a class="mt-3 d-inline-block text-primary">Register Now</a>
            </fieldset>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
include_once "include/footer.php";
