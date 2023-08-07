<?php
  $host = '127.0.0.1';
  $port = '5432';
  $dbname = 'postgres';
  $user = 'postgres';
  $password = 'root';

try {
    $db = new PDO("pgsql:host=$host;port=$port;dbname=$dbname;user=$user;password=$password");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}


function getDB()
{
    global $db;
    return $db;
}