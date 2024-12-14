<?php
include_once "include\header.php";
getHeader("Show Stores");
?>
<style>
	th,
	td {
		text-align: center !important;
	}

	form {
		display: inline-flex;
		align-content: center;
	}

	.swal2-confirm.swal2-styled {
		background-color: #3085d6;
	}
</style>
<div class="main-panel">
	<div class="card-body">
		<div class="content py-5">
			<?php
			require_once "../config/DB_Connection.php";
			$result = DB_Connection::getStores();
			if ($result->num_rows > 0) {
			?>
				<table class="table table-hover">
					<thead>
						<tr>
							<th scope="col">#</th>
							<th scope="col">Image</th>
							<th scope="col">Name</th>
							<th scope="col">Phone</th>
							<th scope="col">Address</th>
							<th scope="col">Category</th>
							<th scope="col">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						while ($row = $result->fetch_assoc()) {
						?>
							<tr>
								<td><?= htmlspecialchars($row["ID"]) ?></td>
								<td><img src="<?= htmlspecialchars($row["Image"]) ?>" width="35px" height="35px"></td>
								<td><?= htmlspecialchars($row["store_name"]) ?></td>
								<td><?= htmlspecialchars($row["Phone"]) ?></td>
								<td><?= htmlspecialchars($row["Address"]) ?></td>
								<td><?= htmlspecialchars($row["Category_name"]) ?></td>
								<td class="demo">
									<form action="storesEdit.php" method="POST">
										<input type="hidden" name="id" value="<?= htmlspecialchars($row["ID"]) ?>">
										<button type="submit" class="btn btn-info btn-sm" style="margin-bottom: 0px !important" data-toggle="tooltip" title="" data-original-title="Edit <?= htmlspecialchars($row["store_name"]) ?>">Edit</button>
									</form>
									<form action="storesDelete.php" method="POST" class="deleteForm">
										<input type="hidden" name="id" value="<?= htmlspecialchars($row["ID"]) ?>">
										<button type="submit" class="btn btn-danger btn-sm" style="margin-bottom: 0px !important" data-toggle="tooltip" title="" data-original-title="Delete <?= htmlspecialchars($row["store_name"]) ?>">Delete</button>
									</form>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } else {
				echo "There is no stores available";
			}
			?>
		</div>
	</div>
</div>
<script>
	$(".deleteForm").on("submit", (e) => {
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure?',
			text: "You won't be able to revert this!",
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, delete it!'
		}).then((result) => {
			if (result.isConfirmed) {
				e.target.submit();
			}
		})
	});
</script>
<?php
include_once "include/footer.php";
if (isset($_GET["status"])) {
	echo "
	<script>
	Swal.fire(
	'{$_GET['title']}',
	'{$_GET['msg']}',
	'{$_GET['status']}',
	);
	</script>";
}
?>
</body>

</html>