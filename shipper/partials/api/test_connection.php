<?php
session_start();
require_once('../../../config/constants.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing database connection...\n";

if ($conn) {
    echo "Database connection successful!\n";
    
    // Test query
    $test_sql = "SELECT COUNT(*) as count FROM tbl_order";
    $test_result = mysqli_query($conn, $test_sql);
    
    if ($test_result) {
        $row = mysqli_fetch_assoc($test_result);
        echo "Total orders in database: " . $row['count'] . "\n";
    } else {
        echo "Error testing query: " . mysqli_error($conn) . "\n";
    }
} else {
    echo "Database connection failed: " . mysqli_connect_error() . "\n";
}
?> 