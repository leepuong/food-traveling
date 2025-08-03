<?php include('config/constants.php'); ?>
<?php $current_page = basename($_SERVER['PHP_SELF']); ?>

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
                <a href="<?php echo SITEURL; ?>index.php" class="home-link">
                    <?php if ($current_page == 'index.php'): ?>
                        <img src="<?php echo SITEURL; ?>images/icon/homeHover.svg" alt="Home" class="home-icon active" />
                    <?php else: ?>
                        <img src="<?php echo SITEURL; ?>images/icon/homeUnHover.svg" alt="Home" class="home-icon" />
                    <?php endif; ?>
                </a>
            </li>
            <li>


                <a href="<?php echo SITEURL; ?>likeIndex.php" class="like-link">
                    <?php if ($current_page == 'likeIndex.php'): ?>
                        <img src="<?php echo SITEURL; ?>images/icon/likeHover.svg" alt="Like" class="home-icon active" />
                    <?php else: ?>
                        <img src="<?php echo SITEURL; ?>images/icon/likeUnHover.svg" alt="Like" class="home-icon" />
                    <?php endif; ?>
                </a>
            </li>
            <li>

                <a href="<?php echo SITEURL; ?>hisIndex.php" class="his-link">
                    <?php if ($current_page == 'hisIndex.php'): ?>
                        <img src="<?php echo SITEURL; ?>images/icon/hisHover.svg" alt="His" class="home-icon active" />
                    <?php else: ?>
                        <img src="<?php echo SITEURL; ?>images/icon/hisUnHover.svg" alt="His" class="home-icon" />
                    <?php endif; ?>
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