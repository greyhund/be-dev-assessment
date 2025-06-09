<?php
//test_connection.php - TEST FOR DB CONNECTION

ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'db.php';

echo "Connected to MySQL!<br>Checking `urls` table…<br>";

try {
    $stmt = $pdo->query("SELECT COUNT(*) AS cnt FROM urls");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "Table `urls` exists, row count = " . $row['cnt'];
} catch (PDOException $e) {
    die("Table check failed: " . $e->getMessage());
}