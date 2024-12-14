<?php
include_once "include/header.php";
getHeader("Create Category");
require_once "../config/DB_Connection.php";
?>
<script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script>
    function fillAllFields(msg, title) {
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
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    extract($_POST);
    $categoryName = trim($categoryName);
    if (empty($categoryName)) {
        echo "<script>fillAllFields('Fill Category Name', 'Fill the required field!');</script>";
    } else if (strlen($categoryName) < 5) {
        echo "<script>fillAllFields('Category Name must be at least 5 chars!', 'Category name is not acceptable');</script>";
    } else {
        try {
            DB_Connection::addNewCategory($categoryName);
            // This code is not working anyways!
            // header("Location: stores.php?status=success&title=Edited Successfully&msg=Store Edited Successfully!");
            echo ("<script>window.location.href='categories.php?status=success&title=Added Successfully&msg=Category Added Successfully!';</script>");
            exit;
        } catch (mysqli_sql_exception) {
            echo "<script>fillAllFields('Catgory name already exists!', 'Choose a unique name!');</script>";
        }
    }
}
?>
<style>
    input::-webkit-file-upload-button {
        visibility: hidden
    }

    input::file-selector-button {
        visibility: hidden
    }

    .input-upload {
        color: transparent
    }

    .input-upload::before {
        content: "Choose an Image";
        display: inline-block;
        background: #48abf7;
        border: 1px solid #2c80ea;
        border-radius: 3px;
        padding: 5px 8px;
        cursor: pointer;
        color: #fff
    }

    .custome {
        flex: 1 0 33.333333%;
        max-width: 49.333333%
    }
</style>
<div class="main-panel">
    <div class="card-body">
        <div class="content py-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Add Category</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 custome">
                                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                                        <div class="form-group">
                                            <label for="categoryName">Category Name</label>
                                            <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Enter Category Name" value="<?= empty($categoryName) ? '' : htmlspecialchars($categoryName) ?>">
                                            <small id="emailHelp2" class="form-text text-muted">Make sure to use a unique name.</small>
                                        </div>
                                        <div class="card-action">
                                            <button class="btn btn-primary btn-lg" type="submit" name="submit" value="submit">Add Category</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4 custome">
                                    <div class="card card-primary bg-primary-gradient">
                                        <div class="card-body">
                                            <h1 class="mb-4 fw-bold">Category News</h1>
                                            <h4 class="mt-3 b-b1 pb-2 mb-5 fw-bold">Visitors Per Day</h4>
                                            <div id="activeUsersChart">
                                                <h4 class="mt-5 pb-3 mb-0 fw-bold">Shop the Best Deals at Our Store Today!</h4>
                                                <li class="d-flex justify-content-between pb-1 pt-1"><small>
                                                        It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.
                                                        The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here,
                                                        content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
                                                    </small></li>
                                            </div>
                                            <h4 class="mt-5 pb-3 mb-0 fw-bold">Top Frequent Questions</h4>
                                            <ul class="list-unstyled">
                                                <li class="d-flex justify-content-between pb-1 pt-1"><small>Why I have to use a unique store name?</small> <span>Answered</span></li>
                                                <li class="d-flex justify-content-between pb-1 pt-1"><small>Can any one get my phone number?</small> <span>Pending...</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    include_once "include/footer.php";
    ?>
    </body>

    </html>