<?php include('partials/menu.php'); ?>
<div class="main-content">
    <div class="wrapper">
        <h1>Add Shipper</h1>
        <br><br>
        <?php
        if (isset($_SESSION['add'])) {
            echo $_SESSION['add'];
            unset($_SESSION['add']);
        }
        ?>
        <form action="" method="POST">
            <table class="tbl-30">
                <tr><td>Full Name:</td><td><input type="text" name="full_name" required></td></tr>
                <tr><td>Phone Number:</td><td><input type="text" name="phone"></td></tr>
                <tr><td>Operating Area:</td><td><input type="text" name="area"></td></tr>
                <tr><td>License Plate:</td><td><input type="text" name="license_plate"></td></tr>
                <tr><td>Username:</td><td><input type="text" name="username" required></td></tr>
                <tr><td>Password:</td><td><input type="password" name="password" required></td></tr>
                <tr><td>GPS Location (optional):</td><td><input type="text" name="gps_location"></td></tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Add Shipper" class="btn-secondary">
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php include('partials/footer.php'); ?>

<?php
if (isset($_POST['submit'])) {
    include('../config/constants.php'); // Make sure database connection is set up here

    // Get data from form
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $area = $_POST['area'];
    $license_plate = $_POST['license_plate'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $gps_location = $_POST['gps_location'];

    // Check if username already exists
    $check_sql = "SELECT * FROM tbl_shipper WHERE username = '$username'";
    $check_res = mysqli_query($conn, $check_sql);
    if (mysqli_num_rows($check_res) > 0) {
        $_SESSION['add'] = "<div class='error'>Username already exists. Please choose another.</div>";
        header("location:" . SITEURL . 'admin/add-shipper.php');
        exit();
    }

    // Insert shipper into database
    $sql = "INSERT INTO tbl_shipper SET 
        full_name='$full_name',
        phone='$phone',
        area='$area',
        license_plate='$license_plate',
        username='$username',
        password='$password',
        gps_location='$gps_location'";

    $res = mysqli_query($conn, $sql) or die(mysqli_error($conn));
    if ($res) {
        $_SESSION['add'] = "<div class='success'>Shipper added successfully.</div>";
        header("location:" . SITEURL . 'admin/manage-shipper.php');
    } else {
        $_SESSION['add'] = "<div class='error'>Failed to add shipper.</div>";
        header("location:" . SITEURL . 'admin/add-shipper.php');
    }
}
?>
