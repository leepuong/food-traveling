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

    <div class="left-menu">
        <ul>
            <li>
                <a href="#">
                    <img src="<?php echo SITEURL; ?>images/icon/homeUnHover.svg" alt="Home" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?php echo SITEURL; ?>images/icon/likeUnHover.svg" alt="like" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?php echo SITEURL; ?>images/icon/hisUnHover.svg" alt="history" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?php echo SITEURL; ?>images/icon/orderUnHover.svg" alt="order" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?php echo SITEURL; ?>images/icon/shippingUnHover.svg" alt="shipping" />
                </a>
            </li>
        </ul>
        <!-- <div class="left-menu-divider"></div> -->
    </div>
    <!-- Navbar Section Ends Here -->