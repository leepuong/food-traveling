<?php
// Include database connection if needed
session_start();
include('config/constants.php');
include('partials-front/rightMenu.php');
include('partials-front/menu.php');

// Kiểm tra nếu chưa đăng nhập
if (empty($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}


// Lấy dữ liệu user hiện tại từ database để điền vào form (nếu muốn hiện dữ liệu cũ)
$sql = "SELECT name, email, phone, address FROM tbl_users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = $_POST['full_name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';

    // Cập nhật dữ liệu vào database
    $sql_update = "UPDATE tbl_users SET name = ?, email = ?, phone = ?, address = ? WHERE username = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('sssss', $full_name, $email, $phone, $address, $username);

    if ($stmt_update->execute()) {
        echo "<script>
    alert('Cập nhật thông tin thành công!');
    window.location.href = 'customer-settings.php';
</script>";
    } else {
        echo "<script>
    alert('Lỗi khi cập nhật thông tin!');
</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Customer Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
        }

        /* Sidebar styling */
        .sidebar {
            width: 80px;
            background-color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-top: 20px;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        }

        .sidebar a {
            color: #ff5c8a;
            font-size: 20px;
            margin: 15px 0;
            text-decoration: none;
            transition: 0.3s;
        }

        .sidebar a:hover {
            transform: scale(1.2);
            color: #ff2e6b;
        }

        /* Main content */
        .main {
            flex: 1;
            padding: 40px;
        }

        .card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            padding: 30px;
            max-width: 600px;
            margin: auto;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        button {
            margin-top: 20px;
            padding: 12px 20px;
            background-color: #ff5c8a;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #ff2e6b;
        }
    </style>
</head>

<body>

    <!-- Sidebar -->


    <!-- Main content -->
    <div class="main">
        <div class="card">
            <h2>Account Settings</h2>
            <form action="" method="POST">
                <label>Full Name</label>
                <input type="text" name="full_name" placeholder="Enter your name">

                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email">

                <label>Phone</label>
                <input type="text" name="phone" placeholder="Enter your phone number">

                <label>Delivery Address</label>
                <input type="text" name="address" placeholder="Enter your address">

                <label>Password</label>
                <a href="update-password.php" style="display: block; width: 100%; background-color: #ff5c8a; padding: 10px; border-radius: 10px; color: white; text-align: center; text-decoration: none; font-size: 14px; cursor: pointer; margin-top: 10px;">
                    Change Password
                </a>
                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

</body>

</html>