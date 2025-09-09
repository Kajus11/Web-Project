<?php


    function getAllFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {

        global $con;

        $getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");

        $getAll->execute();

        $all = $getAll->fetchAll();

        return $all;

    }



    function getTitle() {

        global $pageTitle;

        if (isset($pageTitle)) {

            echo $pageTitle;

        } else {

            echo 'Default';

        }
    }

    ** $theMsg = Echo The Message [ Error | Success | Warning ]

    function redirectHome($theMsg, $url = null, $seconds = 3) {

        if ($url === null) {

            $url = 'index.php';

            $link = 'Homepage';

        } else {

            if (isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '') {

                $url = $_SERVER['HTTP_REFERER'];

                $link = 'Previous Page';

            } else {

                $url = 'index.php';

                $link = 'Homepage';

            }

        }


    echo $theMsg;

    echo "<div class='rounded-lg border p-4 border-blue-200 bg-blue-50 text-blue-800'>You Will Be Redirected to $link After $seconds Seconds.</div>";

    header("refresh:$seconds;url=$url");

    exit();

    }


    function checkItem($select, $from, $value) {

        global $con;

        $statement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

        $statement->execute(array($value));

        $count = $statement->rowCount();

        return $count;

    }


    function countProducts($item, $table) {

        global $con;

        $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

        $stmt2->execute();

        return $stmt2->fetchColumn();

    }


    function getLatest($select, $table, $order, $limit = 5) {

        global $con;

        $getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");

        $getStmt->execute();

        $rows = $getStmt->fetchAll();

        return $rows;

    }

    ** $category_filter = Category ID to filter by

    function getProductsWithFilter($search = '', $category_filter = '') {

        global $con;

        // Build SQL query with search and filter
        $sql = "SELECT products.*, categories.name AS category_name 
                FROM products 
                INNER JOIN categories ON categories.id = products.category_id";

        $conditions = array();
        $params = array();

        if (!empty($search)) {
            $conditions[] = "(products.name LIKE ? OR products.description LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        if (!empty($category_filter)) {
            $conditions[] = "products.category_id = ?";
            $params[] = $category_filter;
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY products.id DESC";

        $stmt = $con->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();

    }

    ** Get Product by ID Function v1.0
    ** Function to get single product by ID
    ** $itemid = Product ID

    function getProductById($itemid) {

        global $con;

        $stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute(array($itemid));
        
        return $stmt->fetch();

    }


    function insertProduct($data) {

        global $con;

        $stmt = $con->prepare("INSERT INTO products(name, description, price, image, stock, category_id, created_at) VALUES(:zname, :zdesc, :zprice, :zimage, :zstock, :zcat, NOW())");
        
        $stmt->execute(array(
            'zname'     => $data['name'],
            'zdesc'     => $data['description'],
            'zprice'    => $data['price'],
            'zimage'    => $data['image'],
            'zstock'    => $data['stock'],
            'zcat'      => $data['category']
        ));

        return $stmt->rowCount();

    }

    ** $id = Product ID

    function updateProduct($data, $id) {

        global $con;


    $stmt = $con->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category_id = ?, image = ? WHERE id = ?");
    $stmt->execute(array($data['name'], $data['description'], $data['price'], $data['stock'], $data['category'], $data['image'], $id));

        return $stmt->rowCount();

    }

    ** $itemid = Product ID

    function deleteProduct($itemid) {

        global $con;

        $stmt = $con->prepare("DELETE FROM products WHERE id = :zid");
        $stmt->bindParam(":zid", $itemid);
        $stmt->execute();

        return $stmt->rowCount();

    }


    function validateProductData($data, $image_data = null) {

        $formErrors = array();

        if (empty($data['name'])) {
            $formErrors[] = 'Name Can\'t be <strong>Empty</strong>';
        }
        if (empty($data['description'])) {
            $formErrors[] = 'Description Can\'t be <strong>Empty</strong>';
        }
        if (empty($data['price'])) {
            $formErrors[] = 'Price Can\'t be <strong>Empty</strong>';
        }
        if (empty($data['stock'])) {
            $formErrors[] = 'Stock Can\'t be <strong>Empty</strong>';
        }
        if ($data['category'] == 0) {
            $formErrors[] = 'You Must Choose the <strong>Category</strong>';
        }

        if ($image_data !== null) {
            $allowedExtensions = array("jpeg", "jpg", "png", "gif");
            
            if (!empty($image_data['name'])) {
                $ref = explode('.', $image_data['name']);
                $imageExtension = strtolower(end($ref));
                
                if (!in_array($imageExtension, $allowedExtensions)) {
                    $formErrors[] = 'This Extension Is Not <strong>Allowed</strong>';
                }
                if ($image_data['size'] > 4194304) {
                    $formErrors[] = 'Picture Cant Be Larger Than <strong>4MB</strong>';
                }
            } else {
                $formErrors[] = 'Product Picture Is <strong>Required</strong>';
            }
        }

        return $formErrors;

    }


    function handleImageUpload($image_data) {

        $imageName = $image_data['name'];
        $imageTmp = $image_data['tmp_name'];
        
        $image = rand(0, 10000000000) . '_' . $imageName;
        move_uploaded_file($imageTmp, "uploads/products/" . $image);
        
        return $image;

    }


    function displaySuccessMessage($message, $icon = 'fas fa-check-circle') {

        echo '<div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">';
        echo '<div class="text-green-600"><i class="' . $icon . ' mr-2"></i>' . $message . '</div>';
        echo '</div>';

    }

    ** Display Error Messages Function v1.0
    ** Function to display styled error messages
    ** $errors = Array of error messages

    function displayErrorMessages($errors) {

        foreach($errors as $error) {
            echo '<div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">';
            echo '<div class="text-red-600"><i class="fas fa-exclamation-triangle mr-2"></i>' . $error . '</div>';
            echo '</div>';
        }

    }


    function displayInfoMessage($message, $icon = 'fas fa-info-circle') {

        echo '<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">';
        echo '<div class="text-blue-600"><i class="' . $icon . ' mr-2"></i>' . $message . '</div>';
        echo '</div>';

    }

?>