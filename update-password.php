<?php
session_start();
include('config/constants.php');

// Kiểm tra đăng nhập
if (!isset($_SESSION['user'])) {
    $_SESSION['no-login-message'] = "<div class='error text-center'>Please login to update password.</div>";
    header('location:' . SITEURL . 'login.php');
    exit;
}

$username = $_SESSION['user'];

// Xử lý form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $current_password = md5($_POST['current_password']);
    $new_password = md5($_POST['new_password']);
    $confirm_password = md5($_POST['confirm_password']);

    // 1. Kiểm tra mật khẩu cũ
    $sql = "SELECT * FROM tbl_users WHERE email = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $current_password);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        // 2. Kiểm tra mật khẩu mới trùng khớp
        if ($new_password === $confirm_password) {
            // 3. Cập nhật mật khẩu
            $update_sql = "UPDATE tbl_users SET password = ? WHERE email = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ss", $new_password, $username);
            if ($update_stmt->execute()) {
                echo "<script>alert('Password updated successfully!'); window.location.href='index.php';</script>";
            } else {
                echo "<script>alert('Error updating password!');</script>";
            }
        } else {
            echo "<script>alert('New password and confirmation do not match!');</script>";
        }
    } else {
        echo "<script>alert('Current password is incorrect!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Update Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            margin-bottom: 20px;
            color: #333;
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #ff5c8a;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #ff2e6b;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>Update Password</h2>
        <form method="POST">
            <label>Current Password</label>
            <input type="password" name="current_password" required>

            <label>New Password</label>
            <input type="password" name="new_password" required>

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Update</button>
        </form>
    </div>
</body>
</html>
