<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require_once 'config/db.php';

function getUserDetails($userId)
{
    $db = getDB();
    $stmt = $db->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
}

$userDetails = getUserDetails($_SESSION['user_id']);

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Authenticated Page</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .welcome-message {
            margin-bottom: 10px;
        }

        .logout-link {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            color: #333;
            border: 1px solid #333;
            padding: 8px 15px;
            border-radius: 4px;
        }

        .logout-link:hover {
            background-color: #333;
            color: #fff;
        }

        .product-actions {
            margin-top: 30px;
        }

        .product-actions a {
            display: inline-block;
            margin-right: 10px;
            text-decoration: none;
            color: #333;
            border: 1px solid #333;
            padding: 8px 15px;
            border-radius: 4px;
        }

        .product-actions a:hover {
            background-color: #333;
            color: #fff;
        }
    </style>

</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($userDetails['username']); ?>!</h2>
    <p class="welcome-message" >This is an authenticated page. Only logged-in users can access it.</p>
    <a href="?logout" class="logout-link">Logout</a>

    <div class="product-actions">
   
    <a href="add_product.php">Add Product</a>

    <a href="products.php">View Products</a>
    </div>
</body>
</html>
