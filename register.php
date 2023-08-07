<?php

session_start();


if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}


require_once 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $stmt = $db->prepare("SELECT *  FROM users WHERE username=?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['error']='This UserName Doesnt Available '; 
         
      
           
        }else{

            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $hashedPassword]);
            echo 'Registration successful. You can now <a href="login.php">login</a>.';
        }

    } catch (PDOException $e) {
        echo 'Registration failed: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        /* styles.css */

body {
    font-family: Arial, sans-serif;
    background-color: #f2f2f2;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    padding: 20px;
    max-width: 400px;
}

h2 {
    margin-top: 0;
    text-align: center;
    color: #333;
}

form {
    display: flex;
    flex-direction: column;
}

label {
    margin-bottom: 10px;
}

input[type="text"],
input[type="password"] {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 15px;
}

input[type="submit"] {
    background-color: #007BFF;
    color: #fff;
    border: none;
    padding: 10px;
    border-radius: 4px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
<div class="container">
    <h2>Registration</h2>
    <form method="post" action="">
    <?php if (isset($_SESSION['error'])) : ?>
     <?php  echo $_SESSION['error'];
            $_SESSION['error'] = null;
     ?>
    <?php endif; ?>
        <label>Username: <input type="text" name="username" required></label><br>
        <label>Password: <input type="password" name="password" required></label><br>
        <input type="submit" value="Register">
    </form>
    <br>
    or <a href="login.php">Sign in</a>
</div>
</body>
</html>

