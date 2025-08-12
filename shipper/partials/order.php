<link rel="stylesheet" href="../../../css/shipper.css">

<div class="container">
    <!-- Container for order list -->
    <div id="order-list-container">
        <?php

        //Getting Foods from Database that are active and featured
        //SQL Query
        $sql2 = "SELECT * FROM tbl_order WHERE status='Ordered'";

        //Execute the Query
        $res2 = mysqli_query($conn, $sql2);

        //Count Rows
        $count2 = mysqli_num_rows($res2);

        //CHeck whether food available or not
        if ($count2 > 0) {
            //Food Available
            while ($row = mysqli_fetch_assoc($res2)) {
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

                <div class="order-box" data-order-id="<?php echo $id; ?>">
                    <div class="order-desc">
                        <h4><?php echo $customer_name; ?></h4>
                        <p class="contact"><?php echo $customer_contact; ?></p>
                        <p class="qtyAndPrice">qty<?php echo $qty; ?>, total <?php echo $total; ?></p>
                        <p class="address">
                            <?php echo $customer_address; ?>
                        </p>

                        <br>
                        <div class="btnBox">
                            <button class="accept-order-btn btn btn-primary" data-order-id="<?php echo $id; ?>">Accept</button>
                            <a href="#" class="btn btn-primary">Cancel</a>
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

    <!-- Container for order detail -->
    <div id="order-detail-container" style="display: none;">
        <div class="back-btn-container">
            <button id="back-to-orders" class="btn btn-secondary">← Back to Orders</button>
        </div>
        <div id="order-detail-content"></div>
    </div>
</div>