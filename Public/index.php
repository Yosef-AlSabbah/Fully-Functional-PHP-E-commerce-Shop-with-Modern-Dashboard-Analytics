<?php
include_once "include/header.php";
$number_of_rows_per_page = 3;
$show_from_row = 0;
$number_of_pages = 1;
$page_num = 1;
include_once "../config/paginations.php";
if (isset($_GET["category_id"])) {
	pagination($number_of_rows_per_page, $show_from_row, $number_of_pages, $page_num, "SELECT COUNT(*) FROM `stores` WHERE `category_id` = {$_GET["category_id"]};");
} else {
	pagination($number_of_rows_per_page, $show_from_row, $number_of_pages, $page_num);
}
?>
<style>
	.section-sm {
		padding: 0px 0 70px;
	}

	.searchBar {
		width: 100%;
		margin: auto;
	}

	.search-group {
		border-radius: 5px;
		overflow: hidden;
	}

	.col-lg-3.col-md-4 {
		margin-top: 3%;
	}

	.col-lg-4.col-md-6 {
		transition: .5s;
	}

	.card {
		transition: box-shadow .3s;
	}

	.card:hover {
		box-shadow: 0px 0px 15px 0px #ddd;
	}

	.widget.category-list ul li a,
	.widget.category-list ul li a span {
		transition: all linear 0.1s !important;
	}

	.alert {
		margin-bottom: 16rem;
	}
</style>
<section class="section-sm">
	<div class="container">
		<div class="row searchBar">
			<div class="widget search p-0 col">
				<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" class="input-group search-group" id="myForm" method="POST">
					<input type="text" class="form-control" id="search" name="search" placeholder="Search By Store Name">
					<button type="submit" class="input-group-addon"><i class="px-3" style="font-style: normal;">Search</i></button>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-lg-3 col-md-4">
				<div class="category-sidebar">
					<div class="widget category-list">
						<h4 class="widget-header">All Category</h4>
						<ul class="category-list">
							<li><a href="index">All<span><?= DB_Connection::getTotalNumberStores() ?></span></a></li>
							<?php
							$result = DB_Connection::getCategoriesWithStoreCount();
							while ($row = $result->fetch_assoc()) {
							?>
								<li><a href="?category_id=<?= htmlspecialchars($row["Category ID"]) ?>"><?= htmlspecialchars($row["Category Name"]) ?><span><?= htmlspecialchars($row["Number of Stores"]) ?></span></a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="col-lg-9 col-md-8">
				<div class="product-grid-list">
					<div class="row mt-30">
						<?php
						$result = DB_Connection::getStoresInfo($_POST["search"], $_GET["category_id"], $show_from_row, $number_of_rows_per_page);
						if ($result && $result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
						?>
								<div class="col-lg-4 col-md-6">
									<div class="product-item bg-light">
										<div class="card">
											<div class="thumb-content" style="text-align: center;padding: 20px;">
												<a href="storeInfo?store_id=<?= htmlspecialchars($row["Store ID"]) ?>">
													<img class="card-img-top img-fluid" src="<?= htmlspecialchars($row["Image Path"]) ?>" style="max-width:150px; max-height: 150px;" alt="Card image cap">
												</a>
											</div>
											<div class="card-body">
												<h4 class="card-title"><a href="storeInfo?store_id=<?= htmlspecialchars($row["Store ID"]) ?>"><?= htmlspecialchars($row["Store Name"]) ?></a></h4>
												<ul class="list-inline product-meta">
													<li class="list-inline-item">
														<a href="?category_id=<?= htmlspecialchars($row["Category ID"]) ?>"><i class="fa fa-folder-open-o"></i><?= htmlspecialchars($row["Category Name"]) ?></a>
													</li>
												</ul>
												<p class="card-text">Phone: <?= htmlspecialchars($row["Store Phone"]) ?></p>
												<p class="card-text">Address: <?= htmlspecialchars($row["Store Address"]) ?></p>
												<div class="product-ratings">
													<ul class="list-inline">
														<?php
														for ($i = 1; $i <= 5; $i++) { ?>
															<li class="list-inline-item <?= $i <= $row["Store Rating"] ? "selected" : "" ?>"><i class="fa fa-star"></i></li>
														<?php } ?>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							<?php }
						} else { ?>
							<br><br><br>
							<div class='alert alert-danger' role='alert'>
								<?php if (isset($_POST['search'])) {
									$storeName = !empty(trim($_POST['search'])) ? trim($_POST['search']) : "E M P T Y";
								?>
									<h4 class='alert-heading'>There is no store with name <b><?= $storeName ?></b></h4>
									<p>Check for name of the store and try again...</p>
									<hr>
									<p class='mb-0'>Sorry, There is no store with name <b><?= $storeName ?></b>, Check for name of the store and try again...</p>
								<?php } else if (isset($_GET["category_id"])) {
									$result = DB_Connection::getCategoryName((int) $_GET["category_id"]);
									if ($result->num_rows == 0) {
										echo '<script>window.location.href = "../404/404.html";</script>';
									}
									$result = $result->fetch_array()[0];
									$number_of_pages = 1;
								?>
									<h4 class='alert-heading'>There is no stores in <b><?= htmlspecialchars($result); ?></b> catgory</h4>
									<p>Category <?= htmlspecialchars($result); ?> is empty!</p>
									<hr>
									<p class='mb-0'>Sorry, There is no stores in <b><?= htmlspecialchars($result); ?></b>, Category is currently empty!</p>
								<?php } ?>
							</div>
						<?php }
						?>
					</div>
				</div>
				<?php if (!isset($_POST["search"]) && $number_of_pages > 1) { ?>
					<div class="pagination justify-content-center">
						<nav aria-label="Page navigation example">
							<ul class="pagination">
								<li class="page-item <?= $page_num == 1 ? "disabled" : "" ?>">
									<a class="page-link" href="?<?= isset($_GET['category_id']) ? "category_id=" . htmlspecialchars($_GET['category_id']) . "&" : "" ?>page_num=<?= htmlspecialchars($page_num - 1) ?>" aria-label="Previous">
										<span aria-hidden="true">&laquo;</span>
										<span class="sr-only">Previous</span>
									</a>
								</li>

								<?php for ($i = 1; $i <= $number_of_pages; $i++) { ?>
									<li class="page-item <?= $page_num == $i ? "active" : "" ?>"><a class="page-link" href="?<?= isset($_GET['category_id']) ? "category_id=" . htmlspecialchars($_GET['category_id']) . "&" : "" ?>page_num=<?= htmlspecialchars($i) ?>"><?= htmlspecialchars($i) ?></a></li>
								<?php } ?>
								<li class="page-item <?= $page_num == $number_of_pages ? "disabled" : "" ?>">
									<a class="page-link" href="?<?= isset($_GET['category_id']) ? "category_id=" . htmlspecialchars($_GET['category_id']) . "&" : "" ?>page_num=<?= htmlspecialchars($page_num + 1) ?>" aria-label="Next">
										<span aria-hidden="true">&raquo;</span>
										<span class="sr-only">Next</span>
									</a>
								</li>
							</ul>
						</nav>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</section>
<?php
include_once "../js/autocomplete/autoComplete.php";
include_once "include/footer.php";
