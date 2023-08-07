<?php


session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/db.php';

function deleteProduct($productId)
{
    global $db;
    try {
        $stmt = $db->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$productId]);
        return true;
    } catch (PDOException $e) {
        echo 'Product deletion failed: ' . $e->getMessage();
        return false;
    }
}

try {
    $stmt = $db->query("SELECT * FROM products");
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error get products: ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product List</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        td a {
            display: inline-block;
            margin-right: 10px;
            text-decoration: none;
            color: #333;
        }

        td a.delete {
            color: #e60000;
        }

        td a:hover {
            text-decoration: underline;
        }

        td a.delete:hover {
            color: #cc0000;
        }

        .add-product-link {
            display: block;
            margin-bottom: 20px;
        }
    </style>
</head>


<body>
<?php

include 'topbar.php';
?>

    <h2>Product List</h2>
    
    <a class="add-product-link"  href="add_product.php">
        Add Product
    </a>
    <br>
    <table>
        <tr>
            <th>ID</th>
            <th>Price</th>
            <th>Title</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($products as $product) : ?>
            <tr>
                <td><?php echo $product['id']; ?></td>
                <td><?php echo $product['price']; ?></td>
                <td><?php echo $product['title']; ?></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $product['id']; ?>">Edit</a>
                    <a href="?delete=<?php echo $product['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    <a href="view_product.php?id=<?php echo $product['id']; ?>">View</a> 
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
<?php

if (isset($_GET['delete'])) {
    $productId = $_GET['delete'];

    deleteProduct($productId);

    header('Location: products.php');

    exit();
}
?>

