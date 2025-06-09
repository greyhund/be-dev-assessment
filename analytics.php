<?php

//analytics.php - returns click analytics in JSON formwt
require 'db.php';

// Set JSON header
header('Content-Type: application/json');

// Get short code from query string (?code=abc123)
if (!isset($_GET['code']) || empty($_GET['code'])) {
    http_response_code(400);
    echo json_encode(["error" => "Missing short code."]);
    exit;
}

$code = $_GET['code'];

// Look up analytics
$stmt = $pdo->prepare("SELECT original_url, short_url, click_count, created_at FROM urls WHERE short_url = ?");
$stmt->execute([$code]);
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    http_response_code(404);
    echo json_encode(["error" => "Short URL not found."]);
    exit;
}

// Return analytics as JSON
echo json_encode($data);
