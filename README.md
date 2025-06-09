# Memow URL Shortener

## Creators Note:

Hello! This is my try at the url shortener be-dev-assesment. I worked this without using a framework like Lavarel, though retrospectively it might've made a lot of the work easier for me, especially with pertinence to working the database into it. It is something I will begin learning more about as I move forward with my own projects.

I had a lot of fun working on figuring this out. It definitely taught me a lot as well! Please let me know where I can improve and I look forward to hearing some feedback. Let me know if there is anything I can do on my end or if something doesn't work and thank you for this opportunity! 😸

---

## Prerequisites

1. **XAMPP (or any LAMP/WAMP stack)**
   - Includes Apache, PHP 8.x, and MySQL/MariaDB.
   - Download/Install XAMPP from https://www.apachefriends.org/.

2. **Git (optional, for cloning the repo)**
   - Install from https://git-scm.com/.

3. A web browser for testing (Chrome, Firefox, etc.).

4. (Optional) Postman or cURL for API testing.

---

## 1. Clone or Download the Repository

1. Open a terminal or Command Prompt.
2. Navigate to your local web‐root folder. For XAMPP on Windows, that’s usually:
```

C:\xampp\htdocs

````
3. Clone this repository (or download and unzip it) into a new folder. You can name it anything you like; for example:
```bash
git clone https://github.com/YOUR_USERNAME/url-shortener.git url_shortener
````

* After cloning, your project folder might be:

  ```
  C:\xampp\htdocs\url_shortener
  ```
* If you prefer another location (e.g. `D:\projects\url_shortener`), you must configure Apache to serve that folder (see **Optional: Custom VHost** below).

---

## 2. Create and Import the Database Schema

1. **Start Apache and MySQL** via the XAMPP Control Panel.
2. Open phpMyAdmin by visiting:

   ```
   http://localhost/phpmyadmin
   ```
3. Click **New** (left sidebar) to create a new database:

   * **Database name**: `url_shortener`
   * **Collation**: `utf8mb4_general_ci`
   * Click **Create**.
4. With the empty `url_shortener` selected, click **Import**.

   * Click **Choose file** and select the included `schema.sql`.
   * Click **Go**.
   * This will create the `urls` table:

   ```sql
   CREATE TABLE `urls` (
     `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
     `original_url` TEXT NOT NULL,
     `short_url` VARCHAR(10) NOT NULL UNIQUE,
     `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
     `click_count` INT NOT NULL DEFAULT 0
   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
   ```

---

## 3. Configure Database Credentials

1. Open **`db.php`** in your project folder (e.g., `C:\xampp\htdocs\url_shortener\db.php`):

   ```php
   <?php
   // db.php – sets up a PDO connection to MySQL

   $host   = 'localhost';
   $dbname = 'url_shortener';
   $user   = 'root';     // XAMPP default user
   $pass   = '';         // XAMPP default password (empty string)

   try {
       $pdo = new PDO(
           "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
           $user,
           $pass,
           [
               PDO::ATTR_ERRMODE    => PDO::ERRMODE_EXCEPTION,
               PDO::ATTR_TIMEOUT    => 5
           ]
       );
   } catch (PDOException $e) {
       die("Database connection failed: " . $e->getMessage());
   }
   ```
2. If your MySQL credentials differ (e.g. you set a password for `root`), update `$user` and `$pass` accordingly.
3. **Save** the file.

---

## 4. Verify Basic Connectivity

1. Go to the file named **`test_connection.php`** in the same folder (`C:\xampp\htdocs\url_shortener\test_connection.php`).
2. In your browser, visit:

   ```
   http://localhost/url_shortener/test_connection.php

   ```
3. You should see:

   ```
   Connected to MySQL!
   Checking `urls` table…
   Table `urls` exists, row count = 0
   ```

   * If you see an error, re‐check that `db.php` points at the correct database name and credentials, and that you imported `schema.sql`.

---

## 5. File Overview

```
url_shortener/
├── db.php          ← PDO connection code
├── index.php       ← Browser form UI
├── shorten.php     ← POST endpoint for JSON/form submissions
├── redirect.php    ← Redirects short codes to original URLs
├── analytics.php   ← Returns click analytics in JSON
├── schema.sql      ← SQL dump to create the `urls` table
|── generate.php    ← Helper functions for code generation
└── README.md       ← This file
```

---

## 6. Troubleshooting

* **Blank Page / Hangs**

  1. Enable error reporting by adding to `db.php` or top of `index.php`:

     ```php
     ini_set('display_errors', 1);
     error_reporting(E_ALL);
     ```
  2. Run `test_connection.php` to verify PHP ⇄ MySQL connectivity.

* **Table Doesn’t Exist in Engine (1932)**

  1. Stop MySQL, delete `urls.frm` and `urls.ibd` from `C:\xampp\mysql\data\url_shortener\`, then restart MySQL.
  2. Recreate the table using phpMyAdmin or the SQL block above.

* **403 Forbidden / 404 Not Found**

  * Ensure the project sits directly under your web root (`htdocs/`) or a configured vhost.
  * Use the correct URL path (e.g., `http://localhost/url_shortener/`).

* **Endpoints Not Working**

  * Confirm `db.php` has the correct database name.
  * Verify `require 'db.php';` is at the top of every PHP file that uses `$pdo`.
  * Check Apache’s error log (`C:\xampp\apache\logs\error.log`) for clues.

* **Weird short URL when using cURL**

  * When using cURL, sometimes you will get something like this: `http:\/\/localhost\/url_shortener\/rFeLdy`
  * I believe the `\/` is just something dealing with JSON. When you paste it into the searchbar, it works just fine. Trying to further debug this.

