<?php
session_start();
include('config/constants.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="./css/styleLogin.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    if (isset($_SESSION['forgot-msg'])) {
        echo $_SESSION['forgot-msg'];
        unset($_SESSION['forgot-msg']);
    }
    ?>
    <div class="container">
        <div class="side-image">
            <div class="discount-card">
                <span>Reset<br>Password</span>
            </div>
            <img src="images/icon/RectangleuImg.png" alt="Forgot Password" />
        </div>

        <div class="form-section login-form-box">
            <form action="" method="POST" class="login-form">
                <h2>Forgot Password</h2>

                <div class="switch-user-shipper" style="margin-bottom: 20px;">
                    <label>
                        <input type="radio" name="role" value="user" checked> User
                    </label>
                    <label style="margin-left: 20px;">
                        <input type="radio" name="role" value="shipper"> Shipper
                    </label>
                </div>

                <div class="input-group">
                    <label for="email">Your email address</label>
                    <input type="email" name="email" id="email" required>
                </div>

                <button type="submit" name="submit" class="btn-main">Continue</button>
                <div class="signup-link">
                    Back to <a href="login.php">Sign in</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

<?php
if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'] === 'shipper' ? 'tbl_shipper' : 'tbl_users';

    $sql = "SELECT * FROM $role WHERE email='$email'";
    $res = mysqli_query($conn, $sql);

    if (mysqli_num_rows($res) == 1) {
        $_SESSION['reset_email'] = $email;
        $_SESSION['reset_role'] = $role;
        header("Location: resetPassword.php");
        exit;
    } else {
        $_SESSION['forgot-msg'] = "<div class='error text-center'>Email not found.</div>";
        header("Location: forgotPassword.php");
        exit;
    }
}
?>
