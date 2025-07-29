<?php include('config/constants.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Bar</title>
    <style>
        .search-bar-container {
            width: 100%;
            max-width: 800px;
            margin: 30px auto;
            /* position: fixed; */
            /* z-index: 100; */
            /* left: 50%; */
            /* transform: translateX(-50%); */
        }

        .search-bar {
            display: flex;
            align-items: center;
            background: #fff6f6;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            padding: 12px 20px;
        }

        .search-bar input {
            border: none;
            background: transparent;
            font-size: 1.2rem;
            flex: 1;
            outline: none;
            color: #333;
        }

        .search-bar .icon {
            margin-right: 12px;
            width: 24px;
            height: 24px;
            opacity: 0.5;
        }
    </style>
</head>

<body>
    <?php
    // Xử lý tìm kiếm khi submit
    $search_result = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['search'])) {
        $search = mysqli_real_escape_string($conn, $_POST['search']);
        $sql = "SELECT * FROM tbl_food WHERE title LIKE '%$search%' AND active='Yes'";
        $res = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($res)) {
            $search_result[] = $row;
        }
    }
    ?>

    <div class="search-bar-container" style="width:100%;max-width:800px;margin:30px auto;">
        <form action="<?php echo SITEURL; ?>food-search.php" method="POST">
            <div class="search-bar">
                <img class="icon" src="<?php echo SITEURL; ?>images/icon/searchIcon.svg" alt="search" />
                <input type="search" name="search" placeholder="What would you like to eat?" required>
            </div>
        </form>
    </div>

</body>

</html>