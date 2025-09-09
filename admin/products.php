<?php
ob_start();
session_start();
$pageTitle = 'Products';
if (isset($_SESSION['Username'])) {
    include 'init.php';
    $do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
    
    if ($do == 'Manage') {
        // Get search parameters
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $category_filter = isset($_GET['category']) ? $_GET['category'] : '';
        
        // Get products using function
        $products = getProductsWithFilter($search, $category_filter);
        $cats = getAllFrom("*", "categories", "", "", "name");
?>
        <!-- Search and Filter Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="bg-gradient-to-r from-indigo-600 to-blue-600 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-search mr-3"></i>Search & Filter Products
                </h2>
            </div>
            <div class="p-6">
                <form method="GET" class="space-y-4 md:space-y-0 md:flex md:products-end md:space-x-4">
                    <input type="hidden" name="do" value="Manage">
                    
                    <!-- Search Input -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-search mr-2 text-blue-500"></i>Search Products
                        </label>
                        <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               placeholder="Search by name or description...">
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="md:w-64">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-filter mr-2 text-purple-500"></i>Filter by Category
                        </label>
                        <select name="category" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">All Categories</option>
                            <?php
                                foreach ($cats as $cat) {
                                    $selected = ($category_filter == $cat['id']) ? 'selected' : '';
                                    echo "<option value='" . $cat['id'] . "' $selected>" . htmlspecialchars($cat['name']) . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                    
                    <!-- Search Button -->
                    <div class="md:w-auto">
                        <button type="submit" 
                                class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all transform hover:scale-105">
                            <i class="fas fa-search mr-2"></i>Search
                        </button>
                    </div>
                    
                    <!-- Clear Button -->
                    <?php if (!empty($search) || !empty($category_filter)): ?>
                    <div class="md:w-auto">
                        <a href="products.php?do=Manage" 
                           class="w-full md:w-auto inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                            <i class="fas fa-times mr-2"></i>Clear
                        </a>
                    </div>
                    <?php endif; ?>
                </form>
                
                <!-- Search Results Info -->
                <?php if (!empty($search) || !empty($category_filter)): ?>
                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center text-blue-800">
                        <i class="fas fa-info-circle mr-2"></i>
                        <span class="font-medium">
                            Found <?php echo count($products); ?> product(s) 
                            <?php if (!empty($search)): ?>
                                matching "<?php echo htmlspecialchars($search); ?>"
                            <?php endif; ?>
                            <?php if (!empty($category_filter)): ?>
                                <?php 
                                $cat_name = '';
                                foreach ($cats as $cat) {
                                    if ($cat['id'] == $category_filter) {
                                        $cat_name = $cat['name'];
                                        break;
                                    }
                                }
                                ?>
                                in category "<?php echo htmlspecialchars($cat_name); ?>"
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Products Management Table -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-blue-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-box mr-3"></i>Manage Products
                    </h1>
                    <div class="text-white">
                        <span class="text-sm opacity-90">Total: </span>
                        <span class="font-semibold"><?php echo count($products); ?> products</span>
                    </div>
                </div>
            </div>
            
            <?php if (!empty($products)): ?>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Picture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach($products as $product) { ?>
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex-shrink-0 h-16 w-16">
                                        <?php if (empty($product['image']) || $product['image'] == 'default.png') { ?>
                                            <img class="h-16 w-16 rounded-lg object-cover" src="uploads/default.png" alt="">
                                        <?php } else { ?>
                                            <img class="h-16 w-16 rounded-lg object-cover" src="uploads/products/<?php echo $product['image']; ?>" alt="">
                                        <?php } ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </div>
                                    <div class="text-sm text-gray-500 max-w-xs truncate">
                                        <?php echo htmlspecialchars(substr($product['description'], 0, 50)) . '...'; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                                    $<?php echo number_format($product['price'], 2); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo $product['stock'] > 10 ? 'bg-green-100 text-green-800' : ($product['stock'] > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800'); ?>">
                                        <i class="fas fa-box mr-1"></i><?php echo $product['stock']; ?> units
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-tag mr-1"></i><?php echo htmlspecialchars($product['category_name']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                        <?php echo date('Y-m-d', strtotime($product['created_at'])); ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="products.php?do=Edit&itemid=<?php echo $product['id']; ?>" 
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </a>
                                        <a href="products.php?do=Delete&itemid=<?php echo $product['id']; ?>" 
                                           class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors confirm">
                                            <i class="fas fa-trash mr-1"></i>Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="p-12 text-center">
                <div class="text-6xl text-gray-300 mb-4">
                    <i class="fas fa-search"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No Products Found</h3>
                <p class="text-gray-500 mb-6">
                    <?php if (!empty($search) || !empty($category_filter)): ?>
                        Try adjusting your search criteria or filters.
                    <?php else: ?>
                        There are no products in the database yet.
                    <?php endif; ?>
                </p>
                <?php if (empty($search) && empty($category_filter)): ?>
                <a href="products.php?do=Add" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add First Product
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="mt-6 flex items-center justify-between">
            <a href="products.php?do=Add" 
               class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i>New Product
            </a>
            
            <div class="hidden md:flex items-center space-x-6 text-sm text-gray-600">
                <div class="flex items-center">
                    <i class="fas fa-box text-blue-500 mr-2"></i>
                    <span><?php echo count($products); ?> Products</span>
                </div>
                <div class="flex items-center">
                    <i class="fas fa-list text-purple-500 mr-2"></i>
                    <span><?php echo count($cats); ?> Categories</span>
                </div>
            </div>
        </div>

<?php
    } elseif ($do == 'Add') { ?>
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <i class="fas fa-plus mr-3"></i>Add New Product
                </h1>
            </div>
            <div class="p-6">
                <form action="?do=Insert" method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag mr-2 text-blue-500"></i>Product Name
                            </label>
                            <input type="text" name="name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   required placeholder="Name of The Product">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Price
                            </label>
                            <input type="text" name="price" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   required placeholder="Price of The Product">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-2 text-purple-500"></i>Description
                        </label>
                        <input type="text" name="description" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                               required placeholder="Description of The Product">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-boxes mr-2 text-orange-500"></i>Stock
                            </label>
                            <input type="number" name="stock" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   required placeholder="Stock quantity">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-list mr-2 text-indigo-500"></i>Category
                            </label>
                            <select name="category" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                    required>
                                <option value="0">Select Category...</option>
                                <?php
                                    $allCats = getAllFrom("*", "categories", "", "", "id");
                                    foreach ($allCats as $cat) {
                                        echo "<option value='" . $cat['id'] . "'>" . htmlspecialchars($cat['name']) . "</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-image mr-2 text-pink-500"></i>Product Picture
                        </label>
                        <input type="file" name="picture" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                               required>
                    </div>

                    <div class="flex justify-end space-x-4 pt-6">
                        <a href="products.php" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-colors">
                            <i class="fas fa-times mr-2"></i>Cancel
                        </a>
                        <input type="submit" value="Add Product" 
                               class="px-6 py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white rounded-lg hover:from-green-700 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all transform hover:scale-105 cursor-pointer">
                    </div>
                </form>
            </div>
        </div>

<?php
    } elseif ($do == 'Insert') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo "<div class='bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6'>";
            echo "<h2 class='text-xl font-semibold text-blue-800 mb-4'><i class='fas fa-plus mr-2'></i>Insert Product</h2>";
            
            // Prepare data
            $data = array(
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'stock' => $_POST['stock'],
                'category' => $_POST['category']
            );
            
            // Validate data
            $formErrors = validateProductData($data, $_FILES['picture']);
            
            if (!empty($formErrors)) {
                displayErrorMessages($formErrors);
            } else {
                // Handle image upload
                $data['image'] = handleImageUpload($_FILES['picture']);
                
                // Insert product
                $result = insertProduct($data);
                
                if ($result > 0) {
                    displaySuccessMessage($result . ' Record Inserted');
                    $seconds = 3;
                    displayInfoMessage("You Will Be Redirected After $seconds Seconds.");
                    header("refresh:$seconds;url=products.php");
                    exit();
                }
            }
            echo "</div>";
        } else {
            echo '<div class="bg-red-50 border border-red-200 rounded-xl p-6">';
            echo '<div class="text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i>Sorry You Cant Browse This Page Directly</div>';
            echo '</div>';
        }
        
    } elseif ($do == 'Edit') {
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $item = getProductById($itemid);
        
        if ($item) { ?>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                    <h1 class="text-2xl font-bold text-white flex items-center">
                        <i class="fas fa-edit mr-3"></i>Edit Product
                    </h1>
                </div>
                <div class="p-6">
                    <form action="?do=Update" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <input type="hidden" name="itemid" value="<?php echo $itemid ?>">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-tag mr-2 text-blue-500"></i>Product Name
                                </label>
                                <input type="text" name="name" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       required placeholder="Name of The Product" value="<?php echo htmlspecialchars($item['name']) ?>">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-dollar-sign mr-2 text-green-500"></i>Price
                                </label>
                                <input type="text" name="price" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       required placeholder="Price of The Product" value="<?php echo $item['price'] ?>">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-purple-500"></i>Description
                            </label>
                            <input type="text" name="description" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                   required placeholder="Description of The Product" value="<?php echo htmlspecialchars($item['description']) ?>">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-boxes mr-2 text-orange-500"></i>Stock
                                </label>
                                <input type="number" name="stock" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                       required placeholder="Stock quantity" value="<?php echo $item['stock'] ?>">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-list mr-2 text-indigo-500"></i>Category
                                </label>
                                <select name="category" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                                        required>
                                    <?php
                                        $allCats = getAllFrom("*", "categories", "", "", "id");
                                        foreach ($allCats as $cat) {
                                            echo "<option value='" . $cat['id'] . "'";
                                            if ($item['category_id'] == $cat['id']) { echo ' selected'; }
                                            echo ">" . htmlspecialchars($cat['name']) . "</option>";
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-image mr-2 text-pink-500"></i>Product Picture
                            </label>
                            <input type="file" name="picture" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <div class="mt-2">
                                <span class="text-xs text-gray-500">Current:</span>
                                <?php if (empty($item['image']) || $item['image'] == 'default.png') { ?>
                                    <img class="h-16 w-16 rounded-lg object-cover inline-block ml-2" src="uploads/default.png" alt="">
                                <?php } else { ?>
                                    <img class="h-16 w-16 rounded-lg object-cover inline-block ml-2" src="uploads/products/<?php echo $item['image']; ?>" alt="">
                                <?php } ?>
                            </div>
                        </div>
                        <div class="flex justify-end space-x-4 pt-6">
                            <a href="products.php" 
                               class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <input type="submit" value="Save Product" 
                                   class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all transform hover:scale-105 cursor-pointer">
                        </div>
                    </form>
                </div>
            </div>
        <?php
        } else {
            echo '<div class="bg-red-50 border border-red-200 rounded-xl p-6">';
            echo '<div class="text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i>Theres No Such ID</div>';
            echo '</div>';
        }
        
    } elseif ($do == 'Update') {
        echo "<div class='bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6'>";
        echo "<h2 class='text-xl font-semibold text-blue-800 mb-4'><i class='fas fa-sync mr-2'></i>Update Product</h2>";
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['itemid'];
            $item = getProductById($id); // Fetch the product for image fallback
            $data = array(
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'stock' => $_POST['stock'],
                'category' => $_POST['category'],
                'image' => $item && isset($item['image']) ? $item['image'] : '' // default to current image if exists
            );
            $newImageUploaded = isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK && !empty($_FILES['picture']['name']);
            // Validate data (with image if new uploaded)
            $formErrors = $newImageUploaded ? validateProductData($data, $_FILES['picture']) : validateProductData($data);
            if (!empty($formErrors)) {
                displayErrorMessages($formErrors);
            } else {
                if ($newImageUploaded) {
                    $data['image'] = handleImageUpload($_FILES['picture']);
                }
                $result = updateProduct($data, $id);
                if ($result > 0) {
                    displaySuccessMessage($result . ' Record Updated');
                    $seconds = 3;
                    displayInfoMessage("You Will Be Redirected After $seconds Seconds.");
                    header("refresh:$seconds;url=products.php");
                    exit();
                }
            }
        } else {
            echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">';
            echo '<div class="text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i>Sorry You Cant Browse This Page Directly</div>';
            echo '</div>';
        }
        echo "</div>";
        
    } elseif ($do == 'Delete') {
        echo "<div class='bg-red-50 border border-red-200 rounded-xl p-6 mb-6'>";
        echo "<h2 class='text-xl font-semibold text-red-800 mb-4'><i class='fas fa-trash mr-2'></i>Delete Product</h2>";
        
        $itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
        $check = checkItem('id', 'products', $itemid);
        
        if ($check > 0) {
            $result = deleteProduct($itemid);
            
            if ($result > 0) {
                displaySuccessMessage($result . ' Record Deleted');
                redirectHome("", 'back');
            }
        } else {
            echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">';
            echo '<div class="text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i>This ID is Not Exist</div>';
            echo '</div>';
        }
        echo '</div>';
    }
    
    include $tpl . 'footer.php';
} else {
    header('Location: index.php');
    exit();
}
ob_end_flush();
?>