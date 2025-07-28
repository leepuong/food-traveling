<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <?php
        $role = ''; // Thêm dòng này để tránh lỗi undefined
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        if (isset($_GET['role'])) {
            $role = $_GET['role'];
        }
        ?>
        <h1>Change Password <?php echo $role; ?></h1>
        <br><br>


        <form action="" method="POST">

            <table class="tbl-30">
                <tr>
                    <td>Current Password: </td>
                    <td>
                        <input type="password" name="current_password" placeholder="Current Password">
                    </td>
                </tr>

                <tr>
                    <td>New Password:</td>
                    <td>
                        <input type="password" name="new_password" placeholder="New Password">
                    </td>
                </tr>

                <tr>
                    <td>Confirm Password: </td>
                    <td>
                        <input type="password" name="confirm_password" placeholder="Confirm Password">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <input type="submit" name="submit" value="Change Password" class="btn-secondary">
                    </td>
                </tr>

            </table>

        </form>

    </div>
</div>

<?php

//CHeck whether the Submit Button is Clicked on Not
if (isset($_POST['submit'])) {
    //1. Get the Data from Form
    $id = $_POST['id'];
    $current_password = md5($_POST['current_password']);
    $new_password = md5($_POST['new_password']);
    $confirm_password = md5($_POST['confirm_password']);

    // Determine redirect path based on role
    if ($role == 'admin') {
        $redirect = SITEURL . 'admin/manage-admin.php';
    } elseif ($role == 'staff') {
        $redirect = SITEURL . 'admin/manage-staff.php';
    } elseif ($role == 'shiper') {
        $redirect = SITEURL . 'admin/manage-shiper.php';
    } else {
        $redirect = SITEURL . 'admin/index.php'; // fallback nếu role không hợp lệ
    }

    //2. Check whether the user with current ID and Current Password Exists or Not
    $sql = "SELECT * FROM tbl_user_especial WHERE id=$id AND password='$current_password'";

    //Execute the Query
    $res = mysqli_query($conn, $sql);

    if ($res == true) {
        $count = mysqli_num_rows($res);

        if ($count == 1) {
            if ($new_password == $confirm_password) {
                $sql2 = "UPDATE tbl_user_especial SET password='$new_password' WHERE id=$id";
                $res2 = mysqli_query($conn, $sql2);

                if ($res2 == true) {
                    $_SESSION['change-pwd'] = "<div class='success'>Password Changed Successfully.</div>";
                } else {
                    $_SESSION['change-pwd'] = "<div class='error'>Failed to Change Password.</div>";
                }
            } else {
                $_SESSION['pwd-not-match'] = "<div class='error'>Password Did not Match.</div>";
            }
        } else {
            $_SESSION['user-not-found'] = "<div class='error'>User Not Found.</div>";
        }

        // Redirect based on role
        header('location:' . $redirect);
    }
}


?>