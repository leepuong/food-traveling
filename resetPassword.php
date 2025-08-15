<?php
session_start();
include('config/constants.php');

if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_role'])) {
    header("Location: forgotPassword.php");
    exit;
}

$email = $_SESSION['reset_email'];
$role = $_SESSION['reset_role'];

if (isset($_POST['submit'])) {
    $new_pass = md5($_POST['password']);

    $sql = "UPDATE $role SET password='$new_pass' WHERE email='$email'";
    if (mysqli_query($conn, $sql)) {
        unset($_SESSION['reset_email'], $_SESSION['reset_role']);
        $_SESSION['login'] = "<div class='success'>Password updated successfully. Please login.</div>";
        header("Location: login.php");
        exit;
    } else {
        $error = "<div class='error text-center'>Error updating password.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="./css/styleLogin.css">
</head>

<body>
    <?php if (isset($error)) echo $error; ?>
    <div class="container">
        <div class="side-image">
            <div class="discount-card">
                <span>New<br>Password</span>
            </div>
            <img src="images/icon/RectangleuImg.png" alt="Reset Password" />
        </div>

        <div class="form-section login-form-box">
            <form action="" method="POST" class="login-form">
                <h2>Set New Password</h2>

                <div class="input-group">
                    <label for="password">New password</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <button type="submit" name="submit" class="btn-main">Update Password</button>
                <div class="signup-link">
                    Back to <a href="login.php">Sign in</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
