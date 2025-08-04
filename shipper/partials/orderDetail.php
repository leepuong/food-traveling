<html>

<head>
    <link rel="stylesheet" href="../css/shipper.css">
</head>

<body>

    <div class="container">

        <?php

        //Getting Foods from Database that are active and featured
        //SQL Query
        $IdOrder = $_GET['food_id'] ?? null;

        $sql3 = "SELECT * FROM tbl_order WHERE id='$IdOrder'";



        //Execute the Query
        $res3 = mysqli_query($conn, $sql3);

        //Count Rows
        $count3 = mysqli_num_rows($res3);

        //CHeck whether food available or not
        if ($count3 > 0) {
            //Food Available
            while ($row = mysqli_fetch_assoc($res3)) {
                //Get all the values
                $id = $row['id'];
                $food = $row['food'];
                $price = $row['price'];
                $qty = $row['qty'];
                $total = $row['total'];
                $customer_name = $row['customer_name'];
                $customer_contact = $row['customer_contact'];
                $customer_address = $row['customer_address'];
        ?>

                <div class="order-box">
                    <div class="order-desc">
                        <h4><?php echo $customer_name; ?> deltail</h4>
                        <p class="contact"><?php echo $customer_contact; ?></p>
                        <p class="qtyAndPrice">qty<?php echo $qty; ?>, total <?php echo $total; ?></p>
                        <p class="address">
                            <?php echo $customer_address; ?>
                        </p>

                        <br>
                        <div class="btnBox">
                            <a href="<?php echo SITEURL; ?>order.php?food_id=<?php echo $id; ?>" class="btn btn-primary">Done</a>
                            <a href="<?php echo SITEURL; ?>order.php?food_id=<?php echo $id; ?>" class="btn btn-primary">Cancel</a>
                        </div>
                    </div>
                </div>

        <?php
            }
        } else {
            //Food Not Available 
            echo "<div class='error'>order not available.</div>";
        }

        ?>
    </div>
</body>

</html>