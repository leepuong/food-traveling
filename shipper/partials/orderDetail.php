<html>

<head>
    <link rel="stylesheet" href="../../../css/shipper.css">
</head>

<body>

    <div class="container">

        <?php

        //Get order from session
        $IdOrder = $_SESSION['current_order'];

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
                        <h4><?php echo $customer_name; ?> details</h4>
                        <p class="contact"><?php echo $customer_contact; ?></p>
                        <p class="food"> <?php echo $food?></p>
                        <p class="qtyAndPrice">Qty: <?php echo $qty; ?>, Total: <?php echo $total; ?></p>
                        <p class="address">
                            <?php echo $customer_address; ?>
                        </p>

                        <br>
                        <div class="btnBox">
                            <a href="#" class="btn btn-primary">Done</a>
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
</body>

</html>