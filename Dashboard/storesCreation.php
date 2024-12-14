<?php
include_once "include/header.php";
getHeader("Create Store");

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
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    extract($_POST);
    require_once "include\AdditionFiles\StoreValidation.php";
    $validator = new StoreValidation($_POST, $_FILES);
    $errors = $validator->validate();
    if (empty($errors)) {
        try {
            $imageName = $_FILES['storeImage']['name'];
            $imageExtension = explode(".", $imageName);
            $imgPath = "../users_imgs/" . strtolower($storeName) . "." . strtolower(end($imageExtension));
            $stmt = DB_Connection::addStore(trim($imgPath), trim($storePhoneNumber), trim($storeAddress), (int) $category, trim($storeName));
            move_uploaded_file($_FILES['storeImage']['tmp_name'], $imgPath);

            echo ("<script>window.location.href='stores.php?status=success&title=Added Successfully&msg=Store Added Successfully!';</script>");
            exit;
        } catch (mysqli_sql_exception) {
            echo "<script>somethingWentWrong('Store name already exists!', 'Choose a unique name!');</script>";
        }
    } else {
        echo "<script>somethingWentWrong('Please ensure that all required field values are allowed', 'Something went wrong');</script>";
    }
}

?>
<style>
    input::file-selector-button {
        visibility: hidden;
    }

    /* Chrome, Edge & Safari */
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
        content: "Choose an Image";
        display: inline-block;
        background: var(--upload-background-color, #48abf7);
        background-size: cover;
        background-image: var(--input-upload-image);
        border: 1px solid #2c80ea;
        width: var(--input-upload-width);
        height: var(--input-upload-height);
        border-radius: 3px;
        padding: 5px 8px;
        cursor: pointer;
        color: var(--input-upload-text-color, white);
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
                            <div class="card-title">Add Store</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-lg-4 custome">
                                    <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label for="storeName">Name</label>
                                            <input type="text" class="form-control" id="storeName" name="storeName" placeholder="Enter Store Name" value="<?= empty($storeName) ? '' : htmlspecialchars($storeName) ?>">
                                            <?php if (isset($errors['storeName'])) { ?>
                                                <small id="storeNameCheck" class="form-text text-muted error"> <?= htmlspecialchars($errors['storeName']) ?></small>
                                            <?php } else { ?>
                                                <small id="storeNameCheck" class="form-text text-muted">Make sure to set a unique name.</small>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="storePhoneNumber">Phone Number</label>
                                            <input type="tel" class="form-control" id="storePhoneNumber" name="storePhoneNumber" placeholder="Enter Phone Number" value="<?= empty($storePhoneNumber) ? '' : htmlspecialchars($storePhoneNumber) ?>">
                                            <?php if (isset($errors['storePhoneNumber'])) { ?>
                                                <small id="phoneNumberCheck" class="form-text text-muted error"> <?= htmlspecialchars($errors['storePhoneNumber']) ?></small>
                                            <?php } else { ?>
                                                <small id=" phoneNumberCheck" class="form-text text-muted">We'll never share your phone number with anyone else.</small>
                                            <?php } ?>
                                        </div>
                                        <div class="form-group">
                                            <label for="storeAddress">Address</label>
                                            <input type="text" class="form-control" id="storeAddress" name="storeAddress" placeholder="Enter Store Address" value="<?= empty($storeAddress) ? '' : htmlspecialchars($storeAddress) ?>">
                                            <?php if (isset($errors['storeAddress'])) { ?>
                                                <small id="addressCheck" class="form-text text-muted error"> <?= htmlspecialchars($errors['storeAddress']) ?></small>
                                            <?php } ?>
                                        </div>
                                        <div class=" form-group">
                                            <label for="storeCategory">Catgory</label>
                                            <select class="form-control" id="storeCategory" name="category">
                                                <option disabled selected>Choose Category</option>
                                                <?php
                                                $result = DB_Connection::getCategoriesASC();
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        echo "<option value = '" . htmlspecialchars($row['category_id']) . "'" . ((!empty($category) && $category == $row['category_id']) ? "selected" : "") . ">" . htmlspecialchars($row['Category_name']) . "</option>";
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
                                            <input type="file" class="form-control-file input-upload" id="storeImage" name="storeImage">
                                            <?php if (isset($errors['image'])) { ?>
                                                <small id="imageCheck" class="form-text text-muted error"> <?= htmlspecialchars($errors['image']) ?></small>
                                            <?php } ?>
                                            <script>
                                                const fileInput = document.getElementById("storeImage");
                                                fileInput.addEventListener("change", () => {
                                                    const element = document.querySelector('.input-upload');
                                                    element.style.setProperty('--input-upload-image', 'url( ' + URL.createObjectURL(fileInput.files[0]) + ')');
                                                    element.style.setProperty('--input-upload-width', '50px');
                                                    element.style.setProperty('--input-upload-height', '50px');
                                                    element.style.setProperty('--input-upload-text-color', "TRANSPARENT");
                                                    element.style.setProperty('--upload-background-color', 'TRANSPARENT');
                                                });
                                            </script>
                                        </div>
                                        <div class="card-action">
                                            <button class="btn btn-primary btn-lg" type="submit" name="submit" value="submit">Add Store</button>
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