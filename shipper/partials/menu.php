<?php include('../config/constants.php'); ?>

<html>

<head>

    <link rel="stylesheet" href="../css/shipper.css">
</head>

<body>
    <button id="statusBtn">Loading...</button>

    <?php
    $status = $_GET['status'] ?? null;
    $foodId = $_GET['food_id'] ?? null;

    if ($foodId && $status === 'online') {
        include('orderDetail.php');
    } elseif ($status === 'online') {
        include('order.php');
    }

    ?>

    <script>
        const btn = document.getElementById("statusBtn");

        // Lấy giá trị 'status' từ URL
        const params = new URLSearchParams(window.location.search);
        const status = params.get("status");

        // Gán trạng thái ban đầu của nút
        if (status === "online") {
            btn.classList.add("online");
            btn.textContent = "Online";
        } else {
            btn.classList.add("offline");
            btn.textContent = "Offline";

        }

        btn.addEventListener("click", () => {
            if (btn.classList.contains("offline")) {
                window.location.href = window.location.pathname + '?status=online';
            } else {
                window.location.href = window.location.pathname + '?status=offline';
            }
        });
    </script>
</body>

</html>