<?php include('partials/menu.php'); ?>

<div class="main-content">
    <div class="wrapper">
        <h1>Manage Shipper</h1>
        <br><br>

        <?php 
        if (isset($_SESSION['add'])) {
            echo $_SESSION['add'];
            unset($_SESSION['add']);
        }
        if (isset($_SESSION['delete'])) {
            echo $_SESSION['delete'];
            unset($_SESSION['delete']);
        }
        if (isset($_SESSION['update'])) {
            echo $_SESSION['update'];
            unset($_SESSION['update']);
        }
        ?>

        <a href="add-shipper.php" class="btn-primary">Add Shipper</a>

        <br><br>

        <table class="tbl-full">
            <tr>
                <th>S.N.</th>
                <th>Full Name</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Area</th>
                <th>License Plate</th>
                <th>Status</th>
                <th>Orders</th>
                <th>Rating</th>
                <th>Actions</th>
            </tr>

            <?php 
            include('../config/constants.php');
            $sql = "SELECT * FROM tbl_shipper";
            $res = mysqli_query($conn, $sql);

            if ($res == TRUE) {
                $count = mysqli_num_rows($res);
                $sn = 1;

                if ($count > 0) {
                    while ($row = mysqli_fetch_assoc($res)) {
                        $id = $row['id'];
                        $full_name = $row['full_name'];
                        $username = $row['username'];
                        $phone = $row['phone'];
                        $area = $row['area'];
                        $license_plate = $row['license_plate'];
                        $status = $row['status'];
                        $delivered_orders = $row['delivered_orders'];
                        $rating = $row['rating'];
                        ?>

                        <tr>
                            <td><?php echo $sn++; ?>.</td>
                            <td><?php echo $full_name; ?></td>
                            <td><?php echo $username; ?></td>
                            <td><?php echo $phone; ?></td>
                            <td><?php echo $area; ?></td>
                            <td><?php echo $license_plate; ?></td>
                            <td><?php echo $status; ?></td>
                            <td><?php echo $delivered_orders; ?></td>
                            <td><?php echo $rating; ?> ★</td>
                            <td>
                                <a href="update-shipper.php?id=<?php echo $id; ?>" class="btn-success">Update</a>
                                <a href="delete-shipper.php?id=<?php echo $id; ?>" class="btn-danger" onclick="return confirm('Are you sure you want to delete this shipper?');">Delete</a>
                                <a href="update-password.php?id=<?php echo $id; ?>" class="btn-primary">Change Password</a>
                            </td>
                        </tr>

                        <?php
                    }
                } else {
                    echo "<tr><td colspan='10' class='error'>No shippers found.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='10' class='error'>Failed to retrieve data.</td></tr>";
            }
            ?>
        </table>
    </div>
</div>

<?php include('partials/footer.php'); ?>
