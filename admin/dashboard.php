<?php

	ob_start(); // Output Buffering Start

	session_start();

	if (isset($_SESSION['Username'])) {

		$pageTitle = 'Dashboard';

		include 'init.php';


	$numUsers = 6; // Number Of Latest Users
	$latestUsers = getLatest("*", "users", "id", $numUsers); // Latest Users Array
	$numProducts = 6; // Number Of Latest Products
	$latestProducts = getLatest("*", 'products', 'id', $numProducts); // Latest Products Array
	$numComments = 4;

		?>

		<!-- Dashboard Header with Gradient -->
		<div class="mb-8 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl p-8 text-white shadow-2xl">
			<div class="text-center">
				<div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mx-auto mb-4 flex items-center justify-center">
					<i class="fas fa-chart-line text-3xl text-white"></i>
				</div>
				<h1 class="text-4xl font-bold mb-2 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">Dashboard</h1>
				<p class="text-xl text-blue-100">Welcome back! Here's an overview of your e-commerce platform.</p>
				<div class="mt-4 w-32 h-1 bg-gradient-to-r from-white/30 to-white/60 mx-auto rounded-full"></div>
			</div>
		</div>

		<!-- Stats Cards with Gradients -->
		<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
			<div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
				<div class="flex items-center">
					<div class="p-4 bg-white/20 backdrop-blur-sm rounded-full mr-4">
						<i class="fas fa-users text-2xl"></i>
					</div>
					<div>
						<p class="text-blue-100 font-medium">Total Members</p>
						<p class="text-3xl font-bold">
							<a href="members.php" class="hover:text-blue-200 transition-colors"><?php echo countProducts('id', 'users') ?></a>
						</p>
					</div>
				</div>
			</div>
			
			<div class="bg-gradient-to-br from-green-500 to-green-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
				<div class="flex items-center">
					<div class="p-4 bg-white/20 backdrop-blur-sm rounded-full mr-4">
						<i class="fas fa-box text-2xl"></i>
					</div>
					<div>
						<p class="text-green-100 font-medium">Total Products</p>
						<p class="text-3xl font-bold">
							<a href="products.php" class="hover:text-green-200 transition-colors"><?php echo countProducts('id', 'products') ?></a>
						</p>
					</div>
				</div>
			</div>
			
			<div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
				<div class="flex items-center">
					<div class="p-4 bg-white/20 backdrop-blur-sm rounded-full mr-4">
						<i class="fas fa-comments text-2xl"></i>
					</div>
					<div>
						<p class="text-purple-100 font-medium">Total Feedbacks</p>
						<p class="text-3xl font-bold">
							<a href="comments.php" class="hover:text-purple-200 transition-colors"><?php echo countProducts('id', 'comments') ?></a>
						</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Latest Content Grid with Gradients -->
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
			<!-- Latest Products -->
			<div class="bg-gradient-to-br from-white to-blue-50 rounded-xl shadow-lg border border-blue-100 overflow-hidden">
				<div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 py-4 text-white">
					<div class="flex items-center justify-between">
						<h2 class="text-lg font-semibold flex items-center">
							<i class="fas fa-box mr-2"></i>
							Latest <?php echo $numProducts ?> Products
						</h2>
						<button onclick="togglePanel('products-panel')" class="text-white/80 hover:text-white">
							<i class="fas fa-chevron-down transform transition-transform" id="products-icon"></i>
						</button>
					</div>
				</div>
				<div class="p-6" id="products-panel">
					<div class="space-y-3">
						<?php
							if (! empty($latestProducts)) {
								foreach ($latestProducts as $product) {
									echo '<div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg hover:from-blue-50 hover:to-purple-50 transition-all duration-300 transform hover:scale-105">';
										echo '<span class="text-gray-900 font-medium">' . htmlspecialchars($product['name']) . '</span>';
										echo '<a href="products.php?do=Edit&itemid=' . $product['id'] . '" class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-sm rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-300 transform hover:scale-105">';
											echo '<i class="fas fa-edit mr-1"></i> Edit';
										echo '</a>';
									echo '</div>';
								}
							} else {
								echo '<p class="text-gray-500 text-center py-4">No Products To Show</p>';
							}
						?>
					</div>
				</div>
			</div>

			<!-- Latest Comments -->
			<div class="bg-gradient-to-br from-white to-purple-50 rounded-xl shadow-lg border border-purple-100 overflow-hidden">
				<div class="bg-gradient-to-r from-purple-500 to-pink-600 px-6 py-4 text-white">
					<div class="flex items-center justify-between">
						<h2 class="text-lg font-semibold flex items-center">
							<i class="fas fa-comments mr-2"></i>
							Latest <?php echo $numComments ?> Feedbacks
						</h2>
						<button onclick="togglePanel('comments-panel')" class="text-white/80 hover:text-white">
							<i class="fas fa-chevron-down transform transition-transform" id="comments-icon"></i>
						</button>
					</div>
				</div>
				<div class="p-6" id="comments-panel">
					<div class="space-y-4">
						<?php
							$stmt = $con->prepare("SELECT comments.*, users.username FROM comments INNER JOIN users ON users.id = comments.user_id ORDER BY comments.id DESC LIMIT $numComments");
							$stmt->execute();
							$comments = $stmt->fetchAll();
							if (! empty($comments)) {
								foreach ($comments as $comment) {
									echo '<div class="p-4 bg-gradient-to-r from-gray-50 to-purple-50 rounded-lg hover:from-purple-50 hover:to-pink-50 transition-all duration-300 transform hover:scale-105">';
										echo '<div class="flex items-center mb-2">';
											echo '<a href="members.php?do=Edit&userid=' . $comment['user_id'] . '" class="font-medium text-purple-600 hover:text-purple-800">';
												echo htmlspecialchars($comment['username']);
											echo '</a>';
										echo '</div>';
										echo '<p class="text-gray-700 text-sm">' . htmlspecialchars($comment['comment']) . '</p>';
									echo '</div>';
								}
							} else {
								echo '<p class="text-gray-500 text-center py-4">No Comments To Show</p>';
							}
						?>
					</div>
				</div>
			</div>
		</div>

		<script>
		function togglePanel(panelId) {
			const panel = document.getElementById(panelId);
			const icon = document.getElementById(panelId.replace('-panel', '-icon'));
			
			if (panel.style.display === 'none') {
				panel.style.display = 'block';
				icon.classList.remove('rotate-180');
			} else {
				panel.style.display = 'none';
				icon.classList.add('rotate-180');
			}
		}
		</script>

		<?php


		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>