<?php
require 'db.php';

// Get the short code from the URL query string, ensuring the string isn't NULL or empty
if (!isset($_GET['code']) || empty($_GET['code'])) {
    http_response_code(400);
    echo "Missing short code.";
    exit;
}

$code = $_GET['code'];

// Look up the original URL on the database
$stmt = $pdo->prepare("SELECT original_url FROM urls WHERE short_url = ?");
$stmt->execute([$code]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    http_response_code(404);
    echo "Short URL not found.";
    exit;
}

// Update click count
$update = $pdo->prepare("UPDATE urls SET click_count = click_count + 1 WHERE short_url = ?");
$update->execute([$code]);

// Redirect to original URL
header("Location: " . $row['original_url']);
exit;

