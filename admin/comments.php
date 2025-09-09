<?php

	/*
	================================================
	== Manage Comments Page
	== You Can Edit | Delete | Approve Comments From Here
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Comments';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		// Start Manage Page

		if ($do == 'Manage') { // Manage Members Page

			// Select All Users Except Admin 

			$stmt = $con->prepare("SELECT comments.*, products.name AS product_name, users.username AS member_name FROM comments INNER JOIN products ON products.id = comments.product_id INNER JOIN users ON users.id = comments.user_id ORDER BY comments.id DESC");

			// Execute The Statement

			$stmt->execute();

			// Assign To Variable 

			$comments = $stmt->fetchAll();

			if (! empty($comments)) {

			?>

			<h1 class="text-center">Manage Feedbacks</h1>
			<div class="max-w-7xl mx-auto px-4">
				<div class="overflow-x-auto">
					<table class="main-min-w-full divide-y divide-gray-200 text-center min-w-full divide-y divide-gray-200 min-w-full divide-y divide-gray-200-bordered">
						<tr>
							<td>Feedback</td>
							<td>Item Name</td>
							<td>User Name</td>
							<td>Added Date</td>
							<td>Control</td>
						</tr>
						<?php
							foreach($comments as $comment) {
								echo "<tr>";
									echo "<td>" . htmlspecialchars($comment['content']) . "</td>";
									echo "<td>" . htmlspecialchars($comment['product_name']) . "</td>";
									echo "<td>" . htmlspecialchars($comment['member_name']) . "</td>";
									echo "<td>" . $comment['created_at'] ."</td>";
									echo "<td>
										<a href='comments.php?do=Delete&comid=" . $comment['id'] . "' class='btn btn-danger confirm'><i class='fa fa-close'></i> Delete </a>";
										if ($comment['status'] == 'pending') {
											echo "<a href='comments.php?do=Approve&comid="
													 . $comment['id'] . "' 
												class='btn btn-info activate'>
												<i class='fa fa-check'></i> Approve</a>";
										}
									echo "</td>";
								echo "</tr>";
							}
						?>
						<tr>
					</table>
				</div>
			</div>

			<?php } else {

				echo '<div class="max-w-7xl mx-auto px-4">';
					echo '<div class="nice-message">There\'s No Comments To Show</div>';
				echo '</div>';

			} ?>

		<?php 

		} elseif ($do == 'Delete') { // Delete Page

			echo "<h1 class='text-center'>Delete Comment</h1>";

			echo "<div class='container'>";

				// Check If Get Request comid Is Numeric & Get The Integer Value Of It

				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('c_id', 'comments', $comid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("DELETE FROM comments WHERE c_id = :zid");

					$stmt->bindParam(":zid", $comid);

					$stmt->execute();

					$theMsg = "<div class='rounded-lg border p-4 border-green-200 bg-green-50 text-green-800'>" . $stmt->rowCount() . ' Record Deleted</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="rounded-lg border p-4 rounded-lg border p-4-danger">This ID is Not Exist</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		} elseif ($do == 'Approve') {

			echo "<h1 class='text-center'>Approve Comment</h1>";
			echo "<div class='container'>";

				// Check If Get Request comid Is Numeric & Get The Integer Value Of It

				$comid = isset($_GET['comid']) && is_numeric($_GET['comid']) ? intval($_GET['comid']) : 0;

				// Select All Data Depend On This ID

				$check = checkItem('c_id', 'comments', $comid);

				// If There's Such ID Show The Form

				if ($check > 0) {

					$stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = ?");

					$stmt->execute(array($comid));

					$theMsg = "<div class='rounded-lg border p-4 border-green-200 bg-green-50 text-green-800'>" . $stmt->rowCount() . ' Record Approved</div>';

					redirectHome($theMsg, 'back');

				} else {

					$theMsg = '<div class="rounded-lg border p-4 rounded-lg border p-4-danger">This ID is Not Exist</div>';

					redirectHome($theMsg);

				}

			echo '</div>';

		}

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>