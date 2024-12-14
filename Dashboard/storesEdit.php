<?php
include_once "include/header.php";
getHeader("Edit Store");

require_once "../config/DB_Connection.php";
?>
<script src="../assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>
<script>
    function somethingWentWrong(msg, title) {
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

if (!isset($_POST["id"])) {
    die("You're not allowed to get here!");
} else if (isset($_POST["submit"])) {
    extract($_POST);

    require_once "include\AdditionFiles\StoreValidation.php";
    $validator = new StoreValidation($_POST, $_FILES, TRUE);
    $errors = $validator->validate();
    if (empty($errors)) {
        try {
            if (!($_FILES['storeImage']['error'] == UPLOAD_ERR_NO_FILE)) {
                $imageName = $_FILES['storeImage']['name'];
                $imageExtension = explode(".", $imageName);
                $imgPath = "../users_imgs/" . $storeName . "." . strtolower(end($imageExtension));
                move_uploaded_file($_FILES['storeImage']['tmp_name'], $imgPath);
            }
            // DB_Connection::getInstance()->prepare("UPDATE `stores` SET "  . (!empty($imgPath) ? "`Image`='$imgPath'," : "") . "`Phone`='$storePhoneNumber',`Address`='$storeAddress',`category_id`=$category,`store_name`='$storeName' WHERE `ID` = $id;")->execute();
            DB_Connection::updateStore($id, $imgPath, $storePhoneNumber, $storeAddress, $category, $storeName);
            echo ("<script>window.location.href='stores.php?status=success&title=Edited Successfully&msg=Store Edited Successfully!';</script>");
            exit;
        } catch (mysqli_sql_exception) {
            echo "<script>somethingWentWrong('Store name already exists!', 'Choose a unique name!');</script>";
        }
    } else {
        echo "<script>somethingWentWrong('Please ensure that all required field values are allowed', 'Something went wrong');</script>";
    }
}
$result = DB_Connection::getStoreById($_POST["id"]);
?>
<style>
    input::file-selector-button {
        visibility: hidden;
    }

    input::-webkit-file-upload-button {
        visibility: hidden;
    }

    input::file-selector-button {
        visibility: hidden;
    }

    */ .input-upload {
        color: transparent;
    }

    .input-upload::before {
        content: "";
        display: inline-block;
        background-size: cover;
        background-image: var(--input-upload-color, url(<?= htmlspecialchars($result["Image"]) ?>));
        width: 50px;
        height: 50px;
        border: 1px solid #2c80ea;
        border-radius: 3px;
        padding: 5px 8px;
        cursor: pointer;
        color: white;
    }

    .custome {
        flex: 1 0 33.333333%;
        max-width: 49.333333%;
    }

    .error {
        color: darkred !important;
    }
</style>
<div class="main-panel">
    <div class="card-body">
        <div class="content py-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">Edit Store</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 custome">
                                    <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="post" enctype="multipart/form-data">
                                        <input type="hidden" name="id" value="<?= htmlspecialchars($_POST["id"]) ?>">
                                        <div class="form-group">
                                            <label for="storeName">Name</label>
                                            <input type="text" class="form-control" id="storeName" name="storeName" placeholder="Enter Store Name" value="<?= htmlspecialchars($result["store_name"]) ?>">
                                            <?php if (isset($errors['storeName'])) { ?>
                                                <small id="storeNameCheck" class="form-text text-muted error"> <?= htmlspecialchars($errors['storeName']) ?></small>
                                            <?php } else { ?>
                                                <small id="storeNameCheck" class="form-text text-muted">Make sure to set a unique name.</small>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="storePhoneNumber">Phone Number</label>
                                            <input type="tel" class="form-control" id="storePhoneNumber" name="storePhoneNumber" placeholder="Enter Phone Number" value="<?= htmlspecialchars($result["Phone"]) ?>">
                                            <?php if (isset($errors['storePhoneNumber'])) { ?>
                                                <small id="phoneNumberCheck" class="form-text text-muted error"> <?= htmlspecialchars($errors['storePhoneNumber']) ?></small>
                                            <?php } else { ?>
                                                <small id=" phoneNumberCheck" class="form-text text-muted">We'll never share your phone number with anyone else.</small>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="storeAddress">Address</label>
                                            <input type="text" class="form-control" id="storeAddress" name="storeAddress" placeholder="Enter Store Address" value="<?= htmlspecialchars($result["Address"]) ?>">
                                            <?php if (isset($errors['storeAddress'])) { ?>
                                                <small id="addressCheck" class="form-text text-muted error"> <?= htmlspecialchars($errors['storeAddress']) ?></small>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="storeCategory">Catgory</label>
                                            <select class="form-control" id="storeCategory" name="category">
                                                <?php
                                                $result2 = DB_Connection::getCategoriesASC();
                                                if ($result2->num_rows > 0) {
                                                    while ($row = $result2->fetch_assoc()) {
                                                        echo "<option value = '" . htmlspecialchars($row['category_id']) . "'" . ($result["category_id"] == $row['category_id'] ? "selected" : "") . ">" . htmlspecialchars($row['Category_name']) . "</option>";
                                                    }
                                                } else {
                                                    echo '<small id="categoryHelp2" class="form-text text-muted" style = "color: red">Well never share your phone number with anyone else.</small>';
                                                }
                                                ?>
                                            </select>
                                            <?php if (isset($errors['category'])) { ?>
                                                <small id="catgoryCheck" class="form-text text-muted error"> <?= htmlspecialchars($errors['category']) ?></small>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="storeImage">Image</label>
                                            <input type="file" class="form-control-file input-upload" id="storeImage" name="storeImage" value="<?= htmlspecialchars($result["Image"]) ?>">
                                            <?php if (isset($errors['image'])) { ?>
                                                <small id="imageCheck" class="form-text text-muted error"> <?= htmlspecialchars($errors['image']) ?></small>
                                            <?php } else { ?>
                                                <small id="fileHelp2" class="form-text text-muted">If you dont want to change the current image don't modify this field</small>
                                            <?php } ?>
                                            <script>
                                                const fileInput = document.getElementById("storeImage");
                                                fileInput.addEventListener("change", () => {
                                                    document.querySelector('.input-upload').style.setProperty('--input-upload-color', 'url( ' + URL.createObjectURL(fileInput.files[0]) + ')');
                                                });
                                            </script>
                                        </div>
                                        <div class="card-action">
                                            <button type="submit" class="btn btn-success btn-lg" name="submit" value="submit">Save Changes</button>
                                            <button id="return" class="btn btn-danger btn-lg">Cancel</button>
                                            <script>
                                                $("#return").on("click", (e) => {
                                                    e.preventDefault();
                                                    window.location.href = "stores.php";
                                                });
                                            </script>

                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-4 custome">
                                    <div class="card card-primary bg-primary-gradient">
                                        <div class="card-body">
                                            <h1 class="mb-4 fw-bold">Stores News</h1>
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