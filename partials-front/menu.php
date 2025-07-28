<?php include('config/constants.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Website</title>

    <!-- Link our CSS file -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/leftMenu.css">
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <!-- <section class="navbar">

        <div class="container">
            <div class="logo">
                <a href="#" title="Logo">
                    <img src="images/logo.png" alt="Restaurant Logo" class="img-responsive">
                </a>
            </div>

            <div class="menu text-left">
                <ul>
                    <li>
                        <a href="<?php echo SITEURL; ?>index.php">Home</a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>categories.php">Categories</a>
                    </li>
                    <li>
                        <a href="<?php echo SITEURL; ?>foods.php">Foods</a>
                    </li>
                    <li>
                        <a href="#">Contact</a>
                    </li>
                </ul>
            </div>

            <div class="clearfix"></div>
        </div>
    </section> -->
    <div class="left-menu">
        <ul>
            <li>
                <a href="#">
                    <img src="images/icon-home.svg" alt="Home" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="images/icon-like.svg" alt="Like" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="images/icon-history.svg" alt="History" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="images/icon-order.svg" alt="Your Order" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="images/icon-track.svg" alt="Theo giõi đơn hàng" />
                </a>
            </li>
        </ul>
        <div class="left-menu-divider"></div>
    </div>
    <!-- Navbar Section Ends Here -->