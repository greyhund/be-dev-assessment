<?php
// generate.php - shared generate function for POST or JSON

/**
 * Generate a random alphanumeric code.
 */
function generateShortCode($length = 6) {
    $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    return substr(str_shuffle($chars), 0, $length);
}

/**
 * Given a PDO connection and an original URL, 
 * returns a unique short code (inserts into DB if new).
 *
 * @param PDO    $pdo
 * @param string $originalUrl
 * @return string The short code
 */
function getOrCreateShortCode(PDO $pdo, string $originalUrl): string {
    // Check for an existing mapping
    $stmt = $pdo->prepare("SELECT short_url FROM urls WHERE original_url = ?");
    $stmt->execute([$originalUrl]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        return $row['short_url'];
    }

    // Otherwise generate a new unique code
    do {
        $code = generateShortCode();
        $check = $pdo->prepare("SELECT id FROM urls WHERE short_url = ?");
        $check->execute([$code]);
    } while ($check->rowCount() > 0);

    // 3) Insert the new mapping
    $insert = $pdo->prepare("INSERT INTO urls (original_url, short_url) VALUES (?, ?)");
    $insert->execute([$originalUrl, $code]);

    return $code;
}
