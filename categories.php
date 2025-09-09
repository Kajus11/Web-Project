<?php
    session_start();
    $pageTitle = 'Category Products';
    include 'init.php';
?>

<?php
if (isset($_GET['pageid']) && is_numeric($_GET['pageid'])) {
    $category = intval($_GET['pageid']);
    $perPage = 12;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $perPage;
    $stmtCount = $con->prepare("SELECT COUNT(*) FROM products WHERE category_id = ?");
    $stmtCount->execute([$category]);
    $totalProducts = $stmtCount->fetchColumn();
    $stmt = $con->prepare("SELECT * FROM products WHERE category_id = :category ORDER BY id DESC LIMIT :offset, :perPage");
    $stmt->bindValue(':category', $category, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $allProducts = $stmt->fetchAll();
    function getSingleValue($con, $sql, $parameters) {
        $q = $con->prepare($sql);
        $q->execute($parameters);
        return $q->fetchColumn();
    }
    $myCategory = getSingleValue($con, "SELECT name FROM categories WHERE id=?", [$category]);
    ?>

    <!-- Category Header -->
    <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl shadow-xl p-8 mb-8 text-white">
        <div class="text-center">
            <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mx-auto mb-4 flex items-center justify-center">
                <i class="fas fa-tag text-3xl text-white"></i>
            </div>
            <h1 class="text-4xl font-bold mb-2 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">
                <?php echo htmlspecialchars($myCategory); ?>
            </h1>
            <p class="text-xl text-blue-100">Browse our selection of <?php echo htmlspecialchars($myCategory); ?> products</p>
            <div class="mt-4 w-32 h-1 bg-gradient-to-r from-white/30 to-white/60 mx-auto rounded-full"></div>
        </div>
    </div>

    <!-- Products Grid -->
    <?php if (!empty($allProducts)) { ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($allProducts as $product) { ?>
                <div class="flex flex-col h-full group bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2 border border-gray-100">
                    <div class="relative overflow-hidden">
                        <?php if (empty($product['image']) || $product['image'] == 'default.png') { ?>
                            <img class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500" src="admin/uploads/default.png" alt="Product Image" />
                        <?php } else { ?>
                            <img class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500" src="admin/uploads/products/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" />
                        <?php } ?>
                        <div class="absolute top-4 right-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-full font-bold shadow-lg">
                            $<?php echo number_format($product['price'], 2); ?>
                        </div>
                    </div>
                    <div class="flex flex-col flex-1 p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">
                            <a href="products.php?itemid=<?php echo $product['id']; ?>" class="hover:text-blue-600 transition-colors">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </a>
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-3"><?php echo htmlspecialchars(substr($product['description'], 0, 100)); ?>...</p>
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500">Stock: <?php echo $product['stock']; ?></span>
                        </div>
                        <div class="mt-auto">
                            <button class="w-full h-12 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Add to Cart</span>
                            </button>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <!-- Pagination Controls -->
        <?php
            $totalPages = ceil($totalProducts / $perPage);
            if ($totalPages > 1) {
                echo '<div class="flex justify-center mt-8 space-x-2">';
                $baseUrl = '?pageid=' . $category;
                if ($page > 1) {
                    echo '<a href="' . $baseUrl . '&page=' . ($page - 1) . '" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Previous</a>';
                }
                for ($i = 1; $i <= $totalPages; $i++) {
                    $active = $i == $page ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300';
                    echo '<a href="' . $baseUrl . '&page=' . $i . '" class="px-4 py-2 rounded ' . $active . '">' . $i . '</a>';
                }
                if ($page < $totalPages) {
                    echo '<a href="' . $baseUrl . '&page=' . ($page + 1) . '" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Next</a>';
                }
                echo '</div>';
            }
        ?>
    <?php } else { ?>
        <div class="bg-white rounded-lg shadow-md p-8 text-center">
            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Products Found</h3>
            <p class="text-gray-500">This category doesn't have any products yet.</p>
        </div>
    <?php } ?>

<?php } else { ?>
    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
        <i class="fas fa-exclamation-triangle text-3xl text-red-500 mb-4"></i>
        <h3 class="text-xl font-semibold text-red-700 mb-2">Invalid Category</h3>
        <p class="text-red-600">Please select a valid category to view products.</p>
    </div>
<?php } ?>

<?php include $tpl . 'footer.php'; ?>