<?php
include_once "include/header.php";
?>

<script src="../js/sweetalert2.all.min.js"></script>
<?php
if (isset($_REQUEST["store_id"])) {
	if (isset($_REQUEST["rating"])) {
		DB_Connection::addOrUpdateRating((int) $_REQUEST['store_id'], (int) $_REQUEST['rating']);
		echo "
		<script>
		Swal.fire(
		'Done Successfully',
		'Your Rating added Successfully!',
		'success'
		);
		</script>";
	}
	$result = DB_Connection::getStoresInfoById($_REQUEST['store_id']);
	if ($result->num_rows == 0) {
		die("You're not allowed to get Here");
	}
	$result = $result->fetch_assoc();
	$starsResult = DB_Connection::getUserStoreRating($_REQUEST["store_id"]);
	$num0fStars = 0;
	if ($starsResult->num_rows > 0) {
		$num0fStars = $starsResult->fetch_assoc()['rating'];
	}
} else {
	die("You're not allowed to get Here");
}
?>
<style>
	.starrr {
		transform: scale(3);
	}

	.btn.btn-primary.active {
		padding: 7px 21px;
		cursor: pointer;
	}

	.row.justify-content-center {
		margin-top: 1%;
	}

	.widget.rate {
		display: grid;
		row-gap: 55px;
	}

	.product-ratings ul li>i {
		color: #dedede;
	}

	.product-ratings ul li.selected>i {
		color: #5672f9;
	}
</style>
<section class="section bg-gray">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col">
				<div class="sidebar">
					<div class="widget" style="padding: 20px 30px;">
						<div class="map">
							<div>
								<div class="row p-lg-3 p-sm-5 p-4 align-items-center">
									<div class="col-lg-4 align-self-center">
										<a href="">
											<img width="150" src="<?= htmlspecialchars($result["Image Path"]) ?>" class="img-fluid" alt="">
										</a>
									</div>
									<div class="col-lg-8">
										<div class="row">
											<div class="col-lg-6 col-md-10">
												<div class="ad-listing-content">
													<div>
														<a href="" class="font-weight-bold"><?= htmlspecialchars($result["Store Name"]) ?></a>
													</div>
													<ul class="list-inline mt-2 mb-3">
														<li class="list-inline-item"><a href="index?category_id=<?= htmlspecialchars($result["Category ID"]) ?>"> <i class="fa fa-folder-open-o"></i> <?= htmlspecialchars($result["Category Name"]) ?></a></li>
													</ul>
													<p class="pr-5">Phone: <?= htmlspecialchars($result["Store Phone"]) ?></p>
													<p class="pr-5">Address: <?= htmlspecialchars($result["Store Address"]) ?></p>
												</div>
												<div class="product-ratings pb-3">
													<ul class="list-inline">
														<?php
														for ($i = 1; $i <= 5; $i++) { ?>
															<li class="list-inline-item <?= $i <= $result["Store Rating"] ? "selected" : "" ?>"><i class="fa fa-star"></i></li>
														<?php } ?>
													</ul>
												</div>

											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="widget rate">
						<h5 class="widget-header text-center">What would you rate
							<br>
							this store
						</h5>
						<div id="starrr" class="starrr"></div>
						<form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST" id="ratingForm" class="col text-center">
							<input type="hidden" name="store_id" value="<?= htmlspecialchars($_REQUEST["store_id"]) ?>">
							<input type="hidden" id="rating" name="rating" value="<?= htmlspecialchars($num0fStars) ?>">

							<?php
							if ($starsResult->num_rows > 0) { ?>
								<h6 style="margin-bottom: 3%;">Your Current Rate: <?= htmlspecialchars($num0fStars) ?></h6>
							<?php } ?>
							<button type="submit" class="btn btn-primary active"><?= $starsResult->num_rows > 0 ? "Change Rate" : "Rate Now" ?></button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<?php
include_once "include/footer.php";
?>
<script src="../plugins/raty/jquery.raty-fa.js"></script>
<script>
	$('#starrr').on('click', () => {
		let score = $('#starrr').data('starrr').options.rating;
		$("#rating").attr('value', score);
	});
</script>
<?php


echo "<script>$('#starrr').starrr('setRating', $num0fStars);</script>";
