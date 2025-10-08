<?php
$servername = "localhost";
$username = "root";
$password = "";  // Default XAMPP MySQL password
$dbname = "grad_clearance";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start();  
?>