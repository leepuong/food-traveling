<?php
// Đây là mô phỏng. Bạn có thể thay bằng Google Distance Matrix API để lấy dữ liệu thật.
$lat = $_GET['lat'];
$lng = $_GET['lng'];
$address = $_GET['address'];

// Mô phỏng tính toán — bạn có thể nâng cấp dùng API thật
$estimatedTime = rand(10, 25); // phút ngẫu nhiên

echo json_encode(['duration' => $estimatedTime]);
?>
