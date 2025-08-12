<?php
session_start();

// Include database connection
require_once('../../../config/constants.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $order_id = $_GET['order_id'];

    // Validate inputs
    if (!empty($order_id)) {
        // Get order details from database
        $sql = "SELECT * FROM tbl_order WHERE id = '$order_id'";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            $order = mysqli_fetch_assoc($res);
            
            // Return order details as JSON
            echo json_encode([
                'success' => true,
                'order' => $order
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Order not found']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid order ID']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?> 