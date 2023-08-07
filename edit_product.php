<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// edit_product.php

require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $price = $_POST['price'];
    $title = $_POST['title'];

    try {
        $stmt = $db->prepare("UPDATE products SET price = ?, title = ? WHERE id = ?");
        $stmt->execute([$price, $title, $id]);
        $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        echo 'Product updated successfully. <a href="products.php">Go back to product list</a>';
        
    } catch (PDOException $e) {
        echo 'Product update failed: ' . $e->getMessage();
    }
} else {

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        try {
            $stmt = $db->prepare("SELECT * FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                echo 'Product not found.';
            }
        } catch (PDOException $e) {
            echo 'Error fetching product details: ' . $e->getMessage();
        }
    } else {
        echo 'Product ID not provided.';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
</head>
<body>
<?php

include 'topbar.php';
?>

    <h2>Edit Product</h2>
    <?php if (isset($product)) : ?>
        <form method="post" action="">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <label>Price: <input type="text" name="price" value="<?php echo $product['price']; ?>" required></label><br>
            <label>Title: <input type="text" name="title" value="<?php echo $product['title']; ?>" required></label><br>
            <input type="submit" value="Update Product">
        </form>
    <?php endif; ?>
</body>
</html>
