<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Add Shiper</h1>

        <br><br>

        <?php
        if (isset($_SESSION['add'])) //Checking whether the SEssion is Set of Not
        {
            echo $_SESSION['add']; //Display the SEssion Message if SEt
            unset($_SESSION['add']); //Remove Session Message
        }
        ?>

        <form action="" method="POST">

            <table class="tbl-30">
                <tr>
                    <td>Email: </td>
                    <td>
                        <input type="text" name="email" placeholder="Your Email">
                    </td>
                </tr>

                <tr>
                    <td>Phonenumber: </td>
                    <td>
                        <input type="text" name="phonenumber" placeholder="Your Phone Number">
                    </td>
                </tr>
                <tr>
                    <td>Username: </td>
                    <td>
                        <input type="text" name="username" placeholder="Your Username">
                    </td>
                </tr>

                <tr>
                    <td>Password: </td>
                    <td>
                        <input type="password" name="password" placeholder="Your Password">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Shiper" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>


    </div>
</div>


<?php
//Process the Value from Form and Save it in Database

//Check whether the submit button is clicked or not

if (isset($_POST['submit'])) {
    // Button Clicked
    //echo "Button Clicked";

    //1. Get the Data from form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phonenumber = $_POST['phonenumber'];
    $password = md5($_POST['password']); //Password Encryption with MD5

    //2. SQL Query to Save the data into database
    $sql = "INSERT INTO tbl_user_especial SET 
            username='$username',
            phonenumber='$phonenumber',
            email='$email',
            password='$password',
            role='shipper'
        ";

    //3. Executing Query and Saving Data into Datbase
    $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));

    //4. Check whether the (Query is Executed) data is inserted or not and display appropriate message
    if ($res == TRUE) {
        //Data Inserted
        //echo "Data Inserted";
        //Create a Session Variable to Display Message
        $_SESSION['add'] = "<div class='success'>Staff Added Successfully.</div>";
        //Redirect Page to Manage Admin
        header("location:" . SITEURL . 'admin/manage-shipper.php');
    } else {
        //FAiled to Insert DAta
        //echo "Faile to Insert Data";
        //Create a Session Variable to Display Message
        $_SESSION['add'] = "<div class='error'>Failed to Add Staff.</div>";
        //Redirect Page to Add Admin
        header("location:" . SITEURL . 'admin/add-shipper.php');
    }
}

?>