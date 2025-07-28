<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <title>Create an account</title>
    <link rel="stylesheet" href="./css/styleLogin.css"> <!-- Assume you have a style.css file for styling -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <?php
    include('config/constants.php'); // Include the constants file for database connection

    if (isset($_SESSION['add'])) //Checking whether the SEssion is Set of Not
    {
        echo $_SESSION['add']; //Display the SEssion Message if SEt
        unset($_SESSION['add']); //Remove Session Message
    }
    ?>
    <div class="container">
        <div class="side-image">
            <div class="discount-card">
                <span>30% Off<br>Fitness Meal</span>
            </div>
            <img src="fitness-meal.jpg" alt="Fitness Meal" /> <!-- Replace with your image source -->
        </div>
        <div class="form-section">
            <form action="" method="POST" class="register-form">
                <div class="avatar">
                    <img src="user-avatar.jpg" alt="Avatar" /> <!-- Replace with your avatar image -->
                </div>
                <h2>Create an account</h2>
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required>
                    <span class="toggle-password">Hide</span>
                </div>
                <div class="input-group">
                    <label for="confirm_password">Confirm password</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <span class="toggle-password">Hide</span>
                </div>
                <div class="agreement">
                    <input type="checkbox" name="agree" id="agree" required>
                    <label for="agree">
                        By creating an account, I agree to our
                        <a href="#">Terms of use</a> and
                        <a href="#">Privacy Policy</a>
                    </label>
                </div>
                <button type="submit" name="submit" class="btn-main">Sign up</button>
                <div class="divider">OR</div>
                <div class="social-signup">
                    <a href="#" class="google"><img src="google.png" alt="Google"></a>
                    <a href="#" class="apple"><img src="apple.png" alt="Apple"></a>
                    <a href="#" class="facebook"><img src="facebook.png" alt="Facebook"></a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>


<?php
//Process the Value from Form and Save it in Database

//Check whether the submit button is clicked or not

if (isset($_POST['submit'])) {
    // Button Clicked
    //echo "Button Clicked";

    //1. Get the Data from form
    $email = $_POST['email'];
    $nameAccount = $_POST['email']; // Assuming the name is the same as email for simplicity
    $password = md5($_POST['password']); //Password Encryption with MD5

    //2. SQL Query to Save the data into database
    $sql = "INSERT INTO tbl_users SET 
    email='$email',
    password='$password',
    name='$nameAccount',
    username='$nameAccount'
";

    //3. Executing Query and Saving Data into Datbase
    $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    //4. Check whether the (Query is Executed) data is inserted or not and display appropriate message
    if ($res == TRUE) {
        //Data Inserted
        //echo "Data Inserted";
        //Create a Session Variable to Display Message
        $_SESSION['add'] = "<div class='success'>User Added Successfully.</div>";
        //Redirect Page to Manage Admin
        header("location:" . SITEURL . 'login.php');
    } else {
        //FAiled to Insert DAta
        //echo "Faile to Insert Data";
        //Create a Session Variable to Display Message
        $_SESSION['add'] = "<div class='error'>Failed to Add User.</div>";
        //Redirect Page to Add Admin
        header("location:" . SITEURL . 'register.php');
    }
}

?>