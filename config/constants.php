<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!defined('SITEURL')) define('SITEURL', 'http://localhost/food-traveling/');
if (!defined('LOCALHOST')) define('LOCALHOST', 'localhost');
if (!defined('DB_USERNAME')) define('DB_USERNAME', 'root');
if (!defined('DB_PASSWORD')) define('DB_PASSWORD', '');
if (!defined('DB_NAME')) define('DB_NAME', 'food_order');

$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD);
if (!$conn) {
    die("Kết nối MySQL thất bại: " . mysqli_connect_error());
}

$db_select = mysqli_select_db($conn, DB_NAME);
if (!$db_select) {
    die("Không chọn được database: " . mysqli_error($conn));
}
