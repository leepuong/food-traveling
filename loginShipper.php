<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Sign in</title>
    <link rel="stylesheet" href="./css/styleLogin.css"> <!-- Assume you have a style.css file for styling -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php include('config/constants.php'); ?>
    <?php
    if (isset($_SESSION['login'])) {
        echo $_SESSION['login'];
        unset($_SESSION['login']);
    }

    if (isset($_SESSION['no-login-message'])) {
        echo $_SESSION['no-login-message'];
        unset($_SESSION['no-login-message']);
    }
    ?>
    <div class="container">
        <div class="side-image">
            <div class="discount-card">
                <span>30% Off<br>Fitness Meal</span>
            </div>
            <img src="images/icon/RectangleuImg.png" alt="Fitness Meal" /> <!-- Replace with your image source -->
        </div>
        <div class="form-section login-form-box">
            <form action="" method="POST" class="login-form">
                <h2>Shipper Sign in</h2>
                <div class="social-login">
                    <button type="button" class="google-login">
                        <img src="<?php echo SITEURL; ?>images/icon/SocialGooglelogo.svg" alt="google" /> Continue with Google
                    </button>
                    <button type="button" class="apple-login">
                        <img src="<?php echo SITEURL; ?>images/icon/SocialAppleLogo.svg" alt="apple" /> Continue with Apple
                    </button>
                    <button type="button" class="facebook-login">
                        <img src="<?php echo SITEURL; ?>images/icon/SocialFacebookLogo.svg" alt="facebook" /> Continue with Facebook
                    </button>
                </div>
                <div class="divider">OR</div>
                <div class="input-group">
                    <label for="username">User name or email address</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="input-group">
                    <label for="password">Your password</label>
                    <input type="password" name="password" id="password" required>
                    <span class="toggle-password">Hide</span>
                </div>
                <div class="forgot-password">
                    <a href="#">Forget your password</a>
                </div>
                <button type="submit" name="submit" class="btn-main">Sign in</button>
                <div class="signup-link">
                    Don't have an account? <a href="registerShipper.php">Sign up</a>
                </div>

            </form>
        </div>
    </div>
    <div class="swichToShipperSide">
        <!-- Switch User/Shipper - thêm thuộc tính active cho trạng thái -->
        <div class="switch-user-shipper">
            <a class="switch-btn user-btn " href="login.php">User</a>
            <a class="switch-btn shipper-btn active" href="loginShipper.php">Shipper</a>
        </div>
    </div>
</body>

</html>


<?php

//CHeck whether the Submit Button is Clicked or NOt
if (isset($_POST['submit'])) {
    //Process for Login
    //1. Get the Data from Login form
    // $username = $_POST['username'];
    // $password = md5($_POST['password']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);

    $raw_password = md5($_POST['password']);
    // $raw_password = $_POST['password'];

    $password = mysqli_real_escape_string($conn, $raw_password);

    //2. SQL to check whether the user with username and password exists or not
    $sql = "SELECT * FROM tbl_user_especial WHERE email='$username' AND password='$password' ";

    //3. Execute the Query
    $res = mysqli_query($conn, $sql);

    //4. COunt rows to check whether the user exists or not
    $count = mysqli_num_rows($res);

    if ($count == 1) {
        //User AVailable and Login Success
        $_SESSION['login'] = "<div class='success'>Login Successful.</div>";
        $_SESSION['user'] = $username; //TO check whether the user is logged in or not and logout will unset it

        //REdirect to HOme Page/Dashboard
        header('location:' . SITEURL . 'shipper/index.php');
    } else {
        //User not Available and Login FAil
        $_SESSION['login'] = "<div class='error text-center'>Username or Password did not match.</div>";
        //REdirect to HOme Page/Dashboard
        header('location:' . SITEURL . 'loginShipper.php');
    }
}

?>