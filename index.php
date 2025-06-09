<?php
require 'db.php';
require 'generate.php';

//================================================================================
//VARIABLES
$shortUrl = '';
$error = '';

//================================================================================
//Generates short code
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $originalUrl = trim($_POST['url']);

    if (empty($originalUrl)) {
        $error = "Please enter a URL.";
    } else {
        $shortUrl = getOrCreateShortCode($pdo, $originalUrl);
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Memow URL Shortener</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="homepage_style.css">

    <style>
        /* Input field styling */
        .url-input {
            width: 100%;
            max-width: 400px;
            padding: 0.75rem;
            font-size: 1rem;
            border: 1px solid #bbb;
            border-radius: 4px;
            box-sizing: border-box;
            margin-top: 0.5rem;
        }
        /* Submit button styling */
        .btn-submit {
            margin-top: 1rem;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.2s ease;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        /* Error message */
        .error {
            color: #d9534f;
            margin-top: 1rem;
            font-weight: 500;
        }
        /* Short URL display box */
        .result-box {
            margin-top: 1.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            max-width: 450px;
            word-break: break-all;
        }
        .result-box a {
            color: #28a745;
            text-decoration: none;
        }
        .result-box a:hover {
            text-decoration: underline;
        }
        /* GIF styling */
        #gif-container {
            text-align: center;
            margin-top: 1rem;
            display: none; 
        }
        #gif-container img.celebration-gif {
            max-width: 200px;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <h1>Memow URL Shortener</h1>
        <p class="subtitle">Turn long links into clean, shareable URLs.</p>
    </header>

    <main class="dashboard">
        <form method="POST" action="">
            <label for="url">Enter a long URL:</label><br>
            <input 
                type="url" 
                id="url" 
                name="url" 
                class="url-input" 
                placeholder="https://example.com" 
                required
            >
            <br>
            <button type="submit" class="btn-submit">Shorten It</button>
        </form>

        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if ($shortUrl): ?>
            <!-- GIF container: hidden initially, shown via JS -->
            <div id="gif-container">
                <img src="success.gif" alt="Success!" class="celebration-gif">
                <br>
                <strong>Your short link was successful!</strong>
            </div>

            <!-- The generated short URL -->
            <div class="result-box">
                <strong>Your short link:</strong><br>
                <a 
                    href="<?= htmlspecialchars("http://localhost/Memow.ocean/public/abyss/Project02-urlshortener/" . $shortUrl) ?>" 
                    target="_blank"
                >
                    <?= htmlspecialchars("http://localhost/Memow.ocean/public/abyss/Project02-urlshortener/" . $shortUrl) ?>
                </a>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        &copy; <?= date('Y') ?> Memow. Built with PHP & MySQL.
    </footer>

    <!-- JavaScript to reveal the GIF when a new short URL exists -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const gifContainer = document.getElementById('gif-container');
            if (gifContainer) {
                // Show the GIF immediately
                gifContainer.style.display = 'block';

                setTimeout(() => {
                    gifContainer.style.display = 'none';
                }, 3000);
            }
        });
    </script>
</body>
</html>
