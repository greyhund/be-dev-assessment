<?php

//db.php – sets up a PDO connection to MySQL
$host = 'localhost';
$dbname = 'url_shortener';
$user = 'root'; // default for XAMPP
$pass = '';     // default for XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}