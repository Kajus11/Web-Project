<?php

	/*
	================================================
	== Category Page
	================================================
	*/

	ob_start(); // Output Buffering Start

	session_start();

	$pageTitle = 'Categories';

	if (isset($_SESSION['Username'])) {

		include 'init.php';

		$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

		if ($do == 'Manage') {

			$sort = 'asc';

			$sort_array = array('asc', 'desc');

			if (isset($_GET['sort']) && in_array($_GET['sort'], $sort_array)) {

				$sort = $_GET['sort'];

			}

			   $stmt2 = $con->prepare("SELECT * FROM categories ORDER BY id $sort");
			   $stmt2->execute();
			   $cats = $stmt2->fetchAll();
			   if (!empty($cats)) {
			   ?>
			   
			   <!-- Categories Header -->
			   <div class="mb-8">
				   <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Categories</h1>
				   <p class="text-gray-600">Organize and manage your product categories</p>
			   </div>

			   <!-- Categories Grid -->
			   <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
				   <div class="p-6 border-b border-gray-200">
					   <div class="flex items-center justify-between">
						   <h2 class="text-lg font-semibold text-gray-900 flex items-center">
							   <i class="fas fa-folder mr-2 text-blue-600"></i>
							   Categories
						   </h2>
						   <div class="flex space-x-2">
							   <a href="categories.php?sort=asc" class="px-3 py-2 text-sm <?php echo $sort == 'asc' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-lg transition-colors">
								   <i class="fas fa-sort-alpha-down mr-1"></i> A-Z
							   </a>
							   <a href="categories.php?sort=desc" class="px-3 py-2 text-sm <?php echo $sort == 'desc' ? 'bg-blue-100 text-blue-700' : 'text-gray-600 hover:bg-gray-100'; ?> rounded-lg transition-colors">
								   <i class="fas fa-sort-alpha-up mr-1"></i> Z-A
							   </a>
						   </div>
					   </div>
				   </div>
				   <div class="p-6">
					   <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
						   <?php
							   foreach($cats as $cat) {
								   echo "<div class='bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors group'>";
									   echo "<div class='flex products-start justify-between mb-3'>";
										   echo "<h3 class='font-semibold text-gray-900'>" . (isset($cat['name']) ? htmlspecialchars($cat['name']) : '<span class="text-red-500">No name</span>') . '</h3>';
										   echo "<div class='flex space-x-1 opacity-0 group-hover:opacity-100 transition-opacity'>";
											   echo "<a href='categories.php?do=Edit&catid=" . $cat['id'] . "' class='p-1 text-blue-600 hover:bg-blue-100 rounded transition-colors' title='Edit'>";
												   echo "<i class='fas fa-edit text-sm'></i>";
											   echo "</a>";
											   echo "<a href='categories.php?do=Delete&catid=" . $cat['id'] . "' class='confirm p-1 text-red-600 hover:bg-red-100 rounded transition-colors' title='Delete'>";
												   echo "<i class='fas fa-trash text-sm'></i>";
											   echo "</a>";
										   echo "</div>";
									   echo "</div>";
									   echo "<p class='text-sm text-gray-600'>";
										   if(empty($cat['description'])) { 
											   echo 'No description available'; 
										   } else { 
											   echo htmlspecialchars($cat['description']); 
										   }
									   echo "</p>";
								   echo "</div>";
							   }
						   ?>
					   </div>
				   </div>
			   </div>
			   
			   <!-- Add New Category Button -->
			   <div class="mt-6">
				   <a href="categories.php?do=Add" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
					   <i class="fas fa-plus mr-2"></i>
					   Add New Category
				   </a>
			   </div>
			   
			   <?php
			   } else {
				   echo '<div class="text-center py-12">';
					   echo '<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">';
						   echo '<i class="fas fa-folder-open text-6xl text-gray-300 mb-4"></i>';
						   echo '<h3 class="text-xl font-semibold text-gray-900 mb-2">No Categories Found</h3>';
						   echo '<p class="text-gray-600 mb-6">Get started by creating your first category</p>';
						   echo '<a href="categories.php?do=Add" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">';
							   echo '<i class="fas fa-plus mr-2"></i> Add New Category';
						   echo '</a>';
					   echo '</div>';
				   echo '</div>';
			   }

		} elseif ($do == 'Add') { ?>

			<!-- Add Category Header -->
			<div class="mb-8">
				<h1 class="text-3xl font-bold text-gray-900 mb-2">Add New Category</h1>
				<p class="text-gray-600">Create a new product category for your store</p>
			</div>

			<!-- Add Category Form -->
			<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
				<div class="p-6 border-b border-gray-200">
					<h2 class="text-lg font-semibold text-gray-900 flex items-center">
						<i class="fas fa-plus-circle mr-2 text-green-600"></i>
						Category Information
					</h2>
				</div>
				<div class="p-6">
					<form action="?do=Insert" method="POST" class="space-y-6">
						<!-- Name Field -->
						<div>
							<label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
							<input type="text" 
								   id="name"
								   name="name" 
								   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
								   autocomplete="off" 
								   required="required" 
								   placeholder="Enter category name" />
						</div>

						<!-- Description Field -->
						<div>
							<label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
							<textarea id="description"
									  name="description" 
									  rows="4"
									  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
									  placeholder="Describe the category (optional)"></textarea>
						</div>

						<!-- Submit Button -->
						<div class="flex items-center justify-between pt-4">
							<a href="categories.php" class="inline-flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
								<i class="fas fa-arrow-left mr-2"></i>
								Back to Categories
							</a>
							<button type="submit" class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
								<i class="fas fa-save mr-2"></i>
								Add Category
							</button>
						</div>
					</form>
				</div>
			</div>
					<!-- End Submit Field -->
				</form>
			</div>

			<?php

		} elseif ($do == 'Insert') {

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				echo '<div class="mb-8">';
				echo '<h1 class="text-3xl font-bold text-gray-900 mb-2">Insert Category</h1>';
				echo '</div>';

				// Get Variables From The Form
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];

				// Check If Category Exist in Database
				$check = checkItem("name", "categories", $name);

				if ($check == 1) {
					$theMsg = '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"><i class="fas fa-exclamation-triangle mr-2"></i>Sorry, this category already exists</div>';
					redirectHome($theMsg, 'back');
				} else {
					// Insert Category Info In Database
					$stmt = $con->prepare("INSERT INTO categories(name, description) VALUES(:zname, :zdesc)");
					$stmt->execute(array(
						'zname' => $name,
						'zdesc' => $desc
					));
					// Echo Success Message
					$theMsg = '<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg"><i class="fas fa-check-circle mr-2"></i>' . $stmt->rowCount() . ' Category Added Successfully</div>';
					$seconds = 3;
					echo '<div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg"><i class="fas fa-info-circle mr-2"></i>Redirecting to categories page in ' . $seconds . ' seconds...</div>';
					header("refresh:$seconds;url='categories.php'");
				}

			} else {
				$theMsg = '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"><i class="fas fa-exclamation-triangle mr-2"></i>Sorry, you cannot browse this page directly</div>';
				redirectHome($theMsg, 'back');
			}

		} elseif ($do == 'Edit') {

			// Check If Get Request catid Is Numeric & Get Its Integer Value

			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

			// Select All Data Depend On This ID

			$stmt = $con->prepare("SELECT * FROM categories WHERE id = ?");

			// Execute Query

			$stmt->execute(array($catid));

			// Fetch The Data

			$cat = $stmt->fetch();

			// The Row Count

			$count = $stmt->rowCount();

			// If There's Such ID Show The Form
			if ($count > 0) { ?>

				<!-- Edit Category Header -->
				<div class="mb-8">
					<h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Category</h1>
					<p class="text-gray-600">Update category information</p>
				</div>

				<!-- Edit Category Form -->
				<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
					<div class="p-6 border-b border-gray-200">
						<h2 class="text-lg font-semibold text-gray-900 flex items-center">
							<i class="fas fa-edit mr-2 text-blue-600"></i>
							Edit Category Information
						</h2>
					</div>
					<div class="p-6">
						<form action="?do=Update" method="POST" class="space-y-6">
							<input type="hidden" name="catid" value="<?php echo $catid ?>" />
							
							<!-- Name Field -->
							<div>
								<label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name *</label>
								<input type="text" 
									   id="name"
									   name="name" 
									   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
									   required="required" 
									   placeholder="Enter category name" 
									   value="<?php echo isset($cat['name']) ? htmlspecialchars($cat['name']) : '' ?>" />
							</div>

							<!-- Description Field -->
							<div>
								<label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
								<textarea id="description"
										  name="description" 
										  rows="4"
										  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
										  placeholder="Describe the category (optional)"><?php echo isset($cat['description']) ? htmlspecialchars($cat['description']) : '' ?></textarea>
							</div>

							<!-- Submit Button -->
							<div class="flex items-center justify-between pt-4">
								<a href="categories.php" class="inline-flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
									<i class="fas fa-arrow-left mr-2"></i>
									Back to Categories
								</a>
								<button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
									<i class="fas fa-save mr-2"></i>
									Save Changes
								</button>
							</div>
						</form>
					</div>
			<?php

			// If There's No Such ID Show Error Message
			} else {
				echo '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">';
				echo '<i class="fas fa-exclamation-triangle mr-2"></i>Category not found';
				echo '</div>';
				$theMsg = '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"><i class="fas fa-exclamation-triangle mr-2"></i>There is no category with this ID</div>';
				redirectHome($theMsg);
			}		} elseif ($do == 'Update') {

			echo '<div class="mb-8">';
			echo '<h1 class="text-3xl font-bold text-gray-900 mb-2">Update Category</h1>';
			echo '</div>';

			if ($_SERVER['REQUEST_METHOD'] == 'POST') {

				// Get Variables From The Form
				$id 		= $_POST['catid'];
				$name 		= $_POST['name'];
				$desc 		= $_POST['description'];

				// Update The Database With This Info
				$stmt = $con->prepare("UPDATE categories SET name = ?, description = ? WHERE id = ?");
				$stmt->execute(array($name, $desc, $id));

				// Echo Success Message
				$theMsg = '<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg"><i class="fas fa-check-circle mr-2"></i>' . $stmt->rowCount() . ' Category Updated Successfully</div>';
				$seconds = 3;
				echo $theMsg;
				echo '<div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg mt-4"><i class="fas fa-info-circle mr-2"></i>Redirecting to categories page in ' . $seconds . ' seconds...</div>';
				header("refresh:$seconds;url='categories.php'");

			} else {
				$theMsg = '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"><i class="fas fa-exclamation-triangle mr-2"></i>Sorry, you cannot browse this page directly</div>';
				redirectHome($theMsg);
			}

		} elseif ($do == 'Delete') {

			echo '<div class="mb-8">';
			echo '<h1 class="text-3xl font-bold text-gray-900 mb-2">Delete Category</h1>';
			echo '</div>';

			// Check If Get Request Catid Is Numeric & Get The Integer Value Of It
			$catid = isset($_GET['catid']) && is_numeric($_GET['catid']) ? intval($_GET['catid']) : 0;

			// Select All Data Depend On This ID
			$check = checkItem('id', 'categories', $catid);

			// If There's Such ID Show The Form
			if ($check > 0) {
				$stmt = $con->prepare("DELETE FROM categories WHERE id = :zid");
				$stmt->bindParam(":zid", $catid);
				$stmt->execute();

				$theMsg = '<div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg"><i class="fas fa-check-circle mr-2"></i>' . $stmt->rowCount() . ' Category Deleted Successfully</div>';
				redirectHome($theMsg, 'back');

			} else {
				$theMsg = '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg"><i class="fas fa-exclamation-triangle mr-2"></i>Category not found</div>';
				redirectHome($theMsg);
			}

		}

		include $tpl . 'footer.php';

	} else {

		header('Location: index.php');

		exit();
	}

	ob_end_flush(); // Release The Output

?>