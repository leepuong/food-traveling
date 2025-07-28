<?php include('config/constants.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- Important to make website responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Website</title>

    <!-- Link our CSS file -->
    <link rel="stylesheet" href="css/rightMenu.css">
</head>

<body>
    <!-- Navbar Section Starts Here -->
    <div class="right-menu">
        <ul>
            <li>
                <a href="#">
                    <img src="<?php echo SITEURL; ?>images/icon/settingUnHover.svg" alt="setting" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?php echo SITEURL; ?>images/icon/logoBrandUnHover.svg" alt="brand" />
                </a>
            </li>
            <li>
                <a href="#">
                    <img src="<?php echo SITEURL; ?>images/icon/logoutUnHover.svg" alt="logout" />
                </a>
            </li>
        </ul>
    </div>
    <!-- Navbar Section Ends Here -->