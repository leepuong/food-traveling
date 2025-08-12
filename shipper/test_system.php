<?php
// Test system functionality
session_start();
include('../config/constants.php');

echo "<h1>System Test</h1>";

// Test 1: Database connection
echo "<h2>Test 1: Database Connection</h2>";
if ($conn) {
    echo "✅ Database connection successful<br>";
} else {
    echo "❌ Database connection failed<br>";
}

// Test 2: Check if orders exist
echo "<h2>Test 2: Check Orders</h2>";
$sql = "SELECT COUNT(*) as count FROM tbl_order WHERE status='Ordered'";
$res = mysqli_query($conn, $sql);
if ($res) {
    $row = mysqli_fetch_assoc($res);
    echo "✅ Found " . $row['count'] . " orders with status 'Ordered'<br>";
} else {
    echo "❌ Error querying orders: " . mysqli_error($conn) . "<br>";
}

// Test 3: Check session
echo "<h2>Test 3: Session</h2>";
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "✅ Session is active<br>";
} else {
    echo "❌ Session is not active<br>";
}

// Test 4: Check file paths
echo "<h2>Test 4: File Paths</h2>";
$constants_path = '../config/constants.php';
if (file_exists($constants_path)) {
    echo "✅ Constants file exists<br>";
} else {
    echo "❌ Constants file not found at: " . $constants_path . "<br>";
}

echo "<h2>Test Complete!</h2>";
echo "<a href='index.php?status=online'>Go to Shipper Dashboard</a>";
?> 