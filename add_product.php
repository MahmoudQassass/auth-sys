<?php

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}


function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}



require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $price = $_POST['price'];
    $title = $_POST['title'];
    $imageHeight = $_POST['height'];
    $imageWidth = $_POST['width'];
    $productId = null;
    $image_names = $_FILES['images']['name'];
    $new_image_names = null;
  
   
    try {
        $stmt = $db->prepare("INSERT INTO products (price, title) VALUES (?, ?) RETURNING id");
        $stmt->execute([$price, $title]);
        $productId = $stmt->fetchColumn();
   
        echo 'Product added successfully. <a href="index.php">Go back to home page</a> or 
        <a href="products.php">View Products</a>';
    } catch (PDOException $e) {
        echo 'Product addition failed: ' . $e->getMessage();
    }

    if (isset($_FILES['images']) && is_array($_FILES['images']['name'])) {

          
    foreach ($_FILES['images']['name'] as $image) {
        $imageFileType = strtolower(pathinfo($image, PATHINFO_EXTENSION));
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        
        if (!in_array($imageFileType, $allowedExtensions)) {
            die('Error: Only JPG, JPEG, PNG, and GIF images are allowed.');
        }
    }

        $uploadDir = 'images/'.'product-'.$productId.'/'; 

           // Create the directory if it doesn't exist
           if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

      
        $new_image_names = array();
        foreach ($image_names as $image_name) {
            $extension = pathinfo($image_name, PATHINFO_EXTENSION);
            $new_name = $productId. '-'. generateRandomString(5) . '.' . $extension;
            $new_image_names[] = $new_name;
        }

        for ($i = 0; $i < count($image_names); $i++) {
            $source_path = $_FILES['images']['tmp_name'][$i];
            $destination_path = $uploadDir . $new_image_names[$i];
            move_uploaded_file($source_path, $destination_path);

            $stmt = $db->prepare("INSERT INTO product_images (product_id, image, width, height) VALUES (?, ?, ?, ?)");
            $stmt->execute([$productId, $uploadDir.$new_image_names[$i], $imageWidth, $imageHeight]);
        }


        echo 'Product added successfully. <a href="index.php">Go back to home page</a>';
    } else {
        echo 'Please select one or more images.';
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Add Product</title>
    <link rel="stylesheet" type="text/css" href="css/add-product-styles.css">
</head>
<body>
<?php

include 'topbar.php';

?>

<div class="container">
    <h2>Add Product</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <label>Title: <input type="text" name="title" required></label><br>
        <label>Price: <input type="text" name="price" required></label><br>
        <label>Image Width: <input type="text" name="width" required></label><br>
        <label>Image Height: <input type="text" name="height" required></label><br>
        <label for="images">Product Images:</label>
        <input type="file" name="images[]" multiple accept="image/*"><br>
        <input type="submit" value="Add Product">
    </form>
</div>

</body>

</html>

