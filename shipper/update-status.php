<?php
include('../config/constants.php');

$data = json_decode(file_get_contents("php://input"), true);
$id = (int)$data['id'];
$status = mysqli_real_escape_string($conn, $data['status']);

$sql = "UPDATE tbl_order SET status='$status' WHERE id=$id";
mysqli_query($conn, $sql);

echo json_encode(['success' => true]);
?>
