<?php
session_start();

define('SITEURL', 'http://localhost/FINAL-PROJECT/food-order-website-php/');
define('LOCALHOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'food-order');

$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD);
if (!$conn) {
    die("Kết nối MySQL thất bại: " . mysqli_connect_error());
}

$db_select = mysqli_select_db($conn, DB_NAME);
if (!$db_select) {
    die("Không chọn được database: " . mysqli_error($conn));
}
