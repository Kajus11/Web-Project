<?php
ob_start();
session_start();
$pageTitle = 'Product Details';
include 'init.php';

$item = null;
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
if ($itemid > 0) {
  $stmt = $con->prepare("SELECT products.*, categories.name AS category_name FROM products INNER JOIN categories ON categories.id = products.category_id WHERE products.id = ?");
  $stmt->execute([$itemid]);
  $item = $stmt->fetch();
}

if (!empty($item)) {
?>

<!-- Product Details Section -->
<div class="bg-white rounded-lg shadow-lg overflow-hidden mb-8">
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 p-8">
    <!-- Product Image -->
    <div class="space-y-4">
      <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
        <?php if (empty($item['image']) || $item['image'] == 'default.png') { ?>
          <img class="w-full h-full object-cover" src="admin/uploads/default.png" alt="Product Image" />
        <?php } else { ?>
          <img class="w-full h-full object-cover" src="admin/uploads/products/<?php echo htmlspecialchars($item['image']); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" />
        <?php } ?>
      </div>
    </div>

    <!-- Product Info -->
    <div class="space-y-6">
      <div>
        <h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($item['name']); ?></h1>
        <p class="text-lg text-gray-600"><?php echo htmlspecialchars($item['description']); ?></p>
      </div>

      <!-- Price -->
      <div class="bg-blue-50 rounded-lg p-4">
        <div class="text-3xl font-bold text-blue-600">$<?php echo number_format($item['price'], 2); ?></div>
      </div>

      <!-- Product Details -->
      <div class="space-y-3">
        <div class="flex items-center justify-between py-2 border-b border-gray-200">
          <span class="flex items-center text-gray-600">
            <i class="fas fa-boxes mr-2 text-blue-500"></i>
            Stock Available
          </span>
          <span class="font-semibold text-gray-900"><?php echo htmlspecialchars($item['stock']); ?> units</span>
        </div>

        <div class="flex items-center justify-between py-2 border-b border-gray-200">
          <span class="flex items-center text-gray-600">
            <i class="fas fa-tag mr-2 text-blue-500"></i>
            Category
          </span>
          <a href="categories.php?pageid=<?php echo urlencode($item['category_id']); ?>"
             class="font-semibold text-blue-600 hover:text-blue-800 transition-colors">
            <?php echo htmlspecialchars($item['category_name']); ?>
          </a>
        </div>

        <div class="flex items-center justify-between py-2">
          <span class="flex items-center text-gray-600">
            <i class="fas fa-calendar mr-2 text-blue-500"></i>
            Added Date
          </span>
          <span class="font-semibold text-gray-900"><?php echo date('M d, Y', strtotime($item['created_at'])); ?></span>
        </div>
      </div>

      <!-- Add to Cart Button -->
      <button class="w-full bg-green-500 hover:bg-green-600 text-white py-4 px-6 rounded-lg text-lg font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
        <i class="fas fa-shopping-cart text-xl"></i>
        <span>Add to Cart</span>
      </button>
    </div>
  </div>
</div>

  <!-- Comments Section -->
  <div class="bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
      <i class="fas fa-comments mr-3 text-blue-600"></i>
      Customer Reviews
    </h2>

    <?php if (isset($_SESSION['user'])) { ?>
      <!-- Add Comment Form -->
      <div class="bg-gray-50 rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Your Review</h3>
        <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['id']; ?>" method="POST" class="space-y-4">
          <textarea name="comment"
                    required
                    rows="4"
                    placeholder="Share your thoughts about this product..."
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
          <button type="submit"
                  class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition-colors duration-200 flex items-center space-x-2">
            <i class="fas fa-paper-plane"></i>
            <span>Submit Review</span>
          </button>
        </form>

        <?php
          if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $comment   = trim($_POST['comment']);
            $productid = $item['id'];
            $userid    = $_SESSION['uid'];

            if (!empty($comment)) {
              $stmt = $con->prepare("
                INSERT INTO comments(content, product_id, user_id, created_at)
                VALUES(:zcomment, :zproductid, :zuserid, NOW())
              ");
              $stmt->execute([
                'zcomment'   => $comment,
                'zproductid' => $productid,
                'zuserid'    => $userid
              ]);

              if ($stmt) {
                header('Location: ' . $_SERVER['PHP_SELF'] . '?itemid=' . $item['id'] . '&comment=added');
                exit();
              }
            } else {
              echo '<div class="rounded-lg border p-4 rounded-lg border p-4-danger auto-hide">You must add a comment</div>';
            }
          }

          if (isset($_GET['comment']) && $_GET['comment'] === 'added') {
            echo '<div class="rounded-lg border p-4 rounded-lg border p-4-success auto-hide">Comment added</div>';
          }
        ?>
      </div>
    </div>
  </div>
  <!-- End Add Comment -->
  <?php } else {
    echo '<a href="login.php">Sign in</a> or <a href="register.php">Register</a> to add a comment';
  } ?>

  <hr class="custom-hr">

  <?php
    $stmt = $con->prepare("
      SELECT comments.*, users.username, users.avatar
      FROM comments
      INNER JOIN users ON users.id = comments.user_id
      WHERE product_id = ?
      ORDER BY comments.id DESC
    ");
    $stmt->execute([$item['id']]);
    $comments = $stmt->fetchAll();
  ?>

  <?php foreach ($comments as $comment) { ?>
    <div class="comment-box">
      <div class="grid grid-cols-12 gap-4">
        <div class="sm:col-span-12-span-2 text-center">
          <?php echo htmlspecialchars($comment['username']); ?>
        </div>
        <div class="sm:col-span-12-span-10">
          <p class="lead"><?php echo htmlspecialchars($comment['content']); ?></p>
        </div>
      </div>
    </div>
    <hr class="custom-hr">
  <?php } ?>
</div>

<?php
} else {
  echo '<div class="max-w-7xl mx-auto px-4">';
    echo '<div class="rounded-lg border p-4 rounded-lg border p-4-danger">There\'s no such ID or this item is waiting approval</div>';
  echo '</div>';
}

include $tpl . 'footer.php';
ob_end_flush();
?>