<?php
include_once "include/header.php";
getHeader("Ratings");
require_once "../config/DB_Connection.php";

$number_of_rows_per_page = 5;
$show_from_row = 0;
$number_of_pages = 1;
$page_num = 1;
include_once "../config/paginations.php";
pagination($number_of_rows_per_page, $show_from_row, $number_of_pages, $page_num);
?>
<link rel="stylesheet" href="../js/autocomplete/custome.css">
<style>
	.myHeader.main-header {
		min-height: auto;
		width: 81% !important;
	}

	th,
	td {
		text-align: center !important;
	}

	form {
		display: inline-flex;
		align-content: center;
	}

	.myNav {
		width: 100%;
		min-height: initial !important;
	}

	.myForm {
		width: 100%;
	}

	#search-nav {
		max-width: initial;
	}


	.container-fluid,
	.myNav {
		padding: 0;
	}

	.myHeader,
	.myNav {
		border-radius: 5px;
		transition: all 0s !important;
	}
</style>

<div class="main-panel">
	<div class="card-body">
		<div class="content py-5">
			<div class="main-header myHeader">
				<nav class="navbar navbar-header navbar-expand-lg myNav" data-background-color="blue">
					<div class="container-fluid">
						<div class="collapse" id="search-nav">
							<form class="navbar-left navbar-form nav-search mr-md-3 myForm" id="myForm" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
								<div class="input-group">
									<div class="input-group-prepend">
										<button type="submit" class="btn btn-search pr-1">
											<i class="fa fa-search search-icon"></i>
										</button>
									</div>
									<input type="search" placeholder="Search Using Store Name" class="form-control search" id="search" name="search">
								</div>
							</form>
						</div>
						<ul class="navbar-nav topbar-nav ml-md-auto align-items-center">
							<li class="nav-item toggle-nav-search hidden-caret">
								<a class="nav-link" data-toggle="collapse" href="#search-nav" role="button" aria-expanded="false" aria-controls="search-nav">
									<i class="fa fa-search"></i>
								</a>
							</li>
						</ul>
					</div>
				</nav>
			</div>
			<?php

			$result = DB_Connection::getStoresInfo($_POST["search"], $somethingDoesNotExist, $show_from_row, $number_of_rows_per_page);
			if ($result->num_rows > 0) {
			?>
				<table class="table table-hover" style="margin-top: 5%;">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Image</th>
							<th scope="col">Store Name</th>
							<th scope="col">Total Ratings</th>
							<th scope="col">Number of Ratings</th>
							<th scope="col">Store Rating</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($row = $result->fetch_assoc()) {
						?>
							<tr>
								<td><?= htmlspecialchars($row["Store ID"]) ?></td>
								<td><img src="<?= htmlspecialchars($row["Image Path"]) ?>" width="35px" height="35px"></td>
								<td><?= htmlspecialchars($row["Store Name"]) ?></td>
								<td><?= htmlspecialchars($row["Total Ratings"]) ?></td>
								<td><?= $row["Number of Ratings"] != 0 ? htmlspecialchars($row["Number of Ratings"]) : "" ?></td>
								<td><?= $row["Number of Ratings"] != 0 ? intval(htmlspecialchars($row["Store Rating"])) : "" ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php if (!isset($_POST['search'])) { ?>
					<div style="display: flex; justify-content: center;">
						<ul class="pagination">
							<li class="paginate_button page-item previous <?= $page_num == 1 ? "disabled" : "" ?>" id="multi-filter-select_previous"><a href="?page_num=<?= $page_num - 1 ?>" aria-controls="multi-filter-select" data-dt-idx="0" tabindex="0" class="page-link">&#171;</a></li>
							<?php for ($i = 1; $i <= $number_of_pages; $i++) { ?>
								<li class="paginate_button page-item <?= $page_num == $i ? "active" : "" ?>"><a href="?page_num=<?= htmlspecialchars($i) ?>" aria-controls="multi-filter-select" data-dt-idx="<?= htmlspecialchars($i) ?>" tabindex="0" class="page-link"><?= htmlspecialchars($i) ?></a></li>
							<?php } ?>
							<li class="paginate_button page-item next <?= $page_num == $number_of_pages ? "disabled" : "" ?>" id="multi-filter-select_next"><a href="?page_num=<?= htmlspecialchars($page_num + 1) ?>" aria-controls="multi-filter-select" data-dt-idx="8" tabindex="0" class="page-link">&#187;</a></li>
						</ul>
					</div>
				<?php } ?>
			<?php } else { ?>
				<br><br><br>
				<div class='alert alert-danger' role='alert'>
					<h4 class='alert-heading'>There is no store with name <b><?= htmlspecialchars(empty($_POST['search']) ? "B L A N K" : $_POST['search']) ?></b></h4>
					<p>Check for name of the store and try again...</p>
					<hr>
					<p class='mb-0'>Sorry, There is no store with name <b><?= htmlspecialchars(empty($_POST['search']) ? "B L A N K" : $_POST['search']) ?></b>, Check for name of the store and try again...</p>
				</div>;
			<?php } ?>
		</div>
	</div>
</div>
<?php
include_once "include/footer.php";
include_once "../js/autocomplete/autoComplete.php";
?>
<script>
	$("#ui-id-1").on("click", () => {
		$("#myForm").submit();
	})
</script>
</body>

</html>