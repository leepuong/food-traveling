<?php
session_start();

// Include database connection
require_once('../../../config/constants.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    // Validate inputs
    if (!empty($order_id) && !empty($status)) {
        // Store order_id in session
        $_SESSION['current_order'] = $order_id;

        // Update order status in database
        $sql = "UPDATE tbl_order SET status = '$status' WHERE id = '$order_id'";
        $res = mysqli_query($conn, $sql);

        if ($res) {
            echo json_encode(['success' => true, 'message' => 'Order accepted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Database update failed: ' . mysqli_error($conn)]);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid order data']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
