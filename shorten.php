<?php
// shorten.php – JSON POST endpoint for creating or looking up a short URL

require 'db.php';
require 'generate.php';

// If you’d rather manually define your base URL (e.g. "http://localhost/myfolder/"),
// you can uncomment the next line or put it in a config.php:
// define('BASE_URL', 'http://localhost/url_shortener/');

header('Content-Type: application/json');

// Read & decode the incoming JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if (empty($data['url'])) {
    echo json_encode(['error' => 'No URL provided.']);
    exit;
}

$originalUrl = trim($data['url']);

// Get or create short code logic from generate.php
try {
    $shortCode = getOrCreateShortCode($pdo, $originalUrl);
} catch (Exception $e) {
    // unexpected DB error
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
    exit;
}

// Determine the base URL dynamically if not manually defined
if (defined('BASE_URL')) {
    $baseUrl = BASE_URL;
} else {
    $scheme    = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host      = $_SERVER['HTTP_HOST'];              // e.g. 'localhost' or 'example.com'
    $scriptDir = rtrim(dirname($_SERVER['SCRIPT_NAME']), '/\\'); // e.g. '/url_shortener'
    $baseUrl   = $scheme . $host . $scriptDir . '/'; // e.g. 'http://localhost/url_shortener/'
}

// 4) Return the JSON response
echo json_encode([
    'short_url' => $baseUrl . $shortCode
]);

