<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


// view_product.php

require_once 'config/db.php';

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
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .product-details {
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<?php

include 'topbar.php';
?>

    <?php if (isset($product)) : ?>
        <h2>View Product</h2>
        <div class="product-details">
      
            <p><strong>ID:</strong> <?php echo $product['id']; ?></p>
            <p><strong>Price:</strong> <?php echo $product['price']; ?></p>
            <p><strong>Title:</strong> <?php echo $product['title']; ?></p>
          
            
        </div>

        <h3>Product Images</h3>
        <?php
        
        try {
            $stmt = $db->prepare("SELECT * FROM product_images WHERE product_id = ?");
            $stmt->execute([$product['id']]);

            while ($imageRow = $stmt->fetch()) {

                $imagePath = $imageRow['image'];
                echo '<img src="' . $imagePath . '" alt="'.$imagePath.'" class="product-image" width="'.$imageRow['width'].'" height="'.$imageRow['height'].'">';
            }

            if (!$stmt->rowCount()) {
                echo 'No Images';
            }
        } catch (PDOException $e) {
            echo 'Error fetching product images: ' . $e->getMessage();
        }
        ?>
    <?php endif; ?>
</body>
</html>
