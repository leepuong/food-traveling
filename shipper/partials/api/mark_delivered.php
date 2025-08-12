<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

include('../../config/constants.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);
    
    // If JSON parsing failed, try POST data
    if (!$data) {
        $data = $_POST;
    }
    
    $order_id = $data['order_id'] ?? null;
    
    if (!$order_id) {
        echo json_encode([
            'success' => false,
            'message' => 'Order ID is required'
        ]);
        exit;
    }
    
    // Update order status to Delivered
    $sql = "UPDATE tbl_order SET status = 'Delivered' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $order_id);
        $result = mysqli_stmt_execute($stmt);
        
        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Order marked as delivered successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update order status: ' . mysqli_error($conn)
            ]);
        }
        
        mysqli_stmt_close($stmt);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Database error: ' . mysqli_error($conn)
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Only POST method is allowed'
    ]);
}
?> 