
<?php
    ob_start();
    session_start();
    $pageTitle = 'Homepage - eCommerce Store';
    include 'init.php';

    $perPage = 12;
    $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
    $offset = ($page - 1) * $perPage;
    $totalProducts = countProducts('id', 'products');
    $allProducts = [];
    $stmt = $con->prepare("SELECT * FROM products ORDER BY id DESC LIMIT :offset, :perPage");
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->execute();
    $allProducts = $stmt->fetchAll();
?>


<div class="bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 text-white py-20 mb-8 rounded-2xl shadow-2xl">
    <div class="text-center relative">
        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent"></div>
        <h1 class="text-4xl md:text-6xl font-bold mb-4 relative z-10 bg-gradient-to-r from-white to-blue-100 bg-clip-text text-transparent">Explore Our Curated Marketplace</h1>
        <p class="text-xl md:text-2xl text-blue-100 relative z-10">Discover amazing products at great prices</p>
    </div>
</div>

<!-- Products Grid -->
<div class="mb-8">
    <div class="text-center mb-8">
        <h2 class="text-4xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 bg-clip-text text-transparent mb-4">Top Picks This Week</h2>
        <div class="w-24 h-1 bg-gradient-to-r from-blue-600 to-purple-600 mx-auto rounded-full"></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php
            if (!empty($allProducts)) {
                foreach ($allProducts as $product) {
                    echo '<div class="flex flex-col h-full group bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden transform hover:-translate-y-2 border border-gray-100">';
                        echo '<div class="relative overflow-hidden">';
                            if (empty($product['image']) || $product['image'] == 'default.png') {
                                echo '<img class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500" src="admin/uploads/default.png" alt="Product Image" />';
                            } else {
                                echo '<img class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500" src="admin/uploads/products/' . htmlspecialchars($product['image']) . '" alt="' . htmlspecialchars($product['name']) . '" />';
                            }
                            echo '<div class="absolute top-4 right-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white px-4 py-2 rounded-full font-bold shadow-lg">';
                                echo '$' . number_format($product['price'], 2);
                            echo '</div>';
                            echo '<div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>';
                        echo '</div>';

                        echo '<div class="flex flex-col flex-1 p-6">';
                            echo '<h3 class="text-xl font-semibold text-gray-800 mb-2">';
                                echo '<a href="products.php?itemid=' . $product['id'] . '" class="hover:text-blue-600 transition-colors">';
                                    echo htmlspecialchars($product['name']);
                                echo '</a>';
                            echo '</h3>';
                            echo '<p class="text-gray-600 mb-4 line-clamp-3">' . htmlspecialchars(substr($product['description'], 0, 100)) . '...</p>';
                            echo '<div class="flex items-center justify-between mb-4">';
                                echo '<span class="text-sm text-gray-500">Stock: ' . $product['stock'] . '</span>';
                            echo '</div>';
                            echo '<div class="mt-auto">';
                                echo '<button class="w-full h-12 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-2 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 flex items-center justify-center space-x-2">';
                                    echo '<i class="fas fa-shopping-cart"></i>';
                                    echo '<span>Add to Cart</span>';
                                echo '</button>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col-span-12-span-full text-center py-12">';
                    echo '<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">';
                        echo '<i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>';
                        echo '<h3 class="text-xl font-semibold text-gray-900 mb-2">No Products Available</h3>';
                        echo '<p class="text-gray-600">Check back soon for new products!</p>';
                    echo '</div>';
                echo '</div>';
            }
        ?>

    </div>
    <!-- Pagination Controls -->
    <?php
        $totalPages = ceil($totalProducts / $perPage);
        if ($totalPages > 1) {
            echo '<div class="flex justify-center mt-8 space-x-2">';
            if ($page > 1) {
                echo '<a href="?page=' . ($page - 1) . '" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Previous</a>';
            }
            for ($i = 1; $i <= $totalPages; $i++) {
                $active = $i == $page ? 'bg-blue-600 text-white' : 'bg-gray-200 hover:bg-gray-300';
                echo '<a href="?page=' . $i . '" class="px-4 py-2 rounded ' . $active . '">' . $i . '</a>';
            }
            if ($page < $totalPages) {
                echo '<a href="?page=' . ($page + 1) . '" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Next</a>';
            }
            echo '</div>';
        }
    ?>
</div>

<?php
    include $tpl . 'footer.php';
    ob_end_flush();
?>