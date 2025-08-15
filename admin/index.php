<?php include('partials/menu.php'); ?>

<!-- Main Content Section Starts -->
<div class="main-content">
    <div class="wrapper">
        <h1>Dashboard</h1>
        <br><br>
        <?php
        if (isset($_SESSION['login'])) {
            echo $_SESSION['login'];
            unset($_SESSION['login']);
        }
        ?>
        <br><br>

        <div class="col-4 text-center">
            <?php
            $sql = "SELECT * FROM tbl_category";
            $res = mysqli_query($conn, $sql);
            $count = mysqli_num_rows($res);
            ?>
            <h1><?php echo $count; ?></h1>
            <br />Categories
        </div>

        <div class="col-4 text-center">
            <?php
            $sql2 = "SELECT * FROM tbl_food";
            $res2 = mysqli_query($conn, $sql2);
            $count2 = mysqli_num_rows($res2);
            ?>
            <h1><?php echo $count2; ?></h1>
            <br />Foods
        </div>

        <div class="col-4 text-center">
            <?php
            $sql3 = "SELECT * FROM tbl_order";
            $res3 = mysqli_query($conn, $sql3);
            $count3 = mysqli_num_rows($res3);
            ?>
            <h1><?php echo $count3; ?></h1>
            <br />Total Orders
        </div>

        <div class="col-4 text-center">
            <?php
            $sql4 = "SELECT SUM(total) AS Total FROM tbl_order WHERE status='Delivered'";
            $res4 = mysqli_query($conn, $sql4);
            $row4 = mysqli_fetch_assoc($res4);
            $total_revenue = $row4['Total'];
            ?>
            <h1>$<?php echo $total_revenue; ?></h1>
            <br />Revenue Generated
        </div>

        <div class="clearfix"></div>
        <br><br>

        <?php
        // Pie chart data
        $sql_status = "SELECT status, COUNT(*) as total 
                       FROM tbl_order 
                       GROUP BY status";
        $res_status = mysqli_query($conn, $sql_status);
        $statuses = [];
        $totals = [];
        while ($row_status = mysqli_fetch_assoc($res_status)) {
            $statuses[] = $row_status['status'];
            $totals[] = $row_status['total'];
        }

        // Line chart data (Orders per month)
        $sql_month = "SELECT DATE_FORMAT(order_date, '%Y-%m') AS month, COUNT(*) as order_count
                      FROM tbl_order
                      GROUP BY DATE_FORMAT(order_date, '%Y-%m')
                      ORDER BY month ASC";
        $res_month = mysqli_query($conn, $sql_month);
        $months = [];
        $order_counts = [];
        while ($row_month = mysqli_fetch_assoc($res_month)) {
            $months[] = $row_month['month'];
            $order_counts[] = $row_month['order_count'];
        }
        ?>

        <!-- Charts -->
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 30px;">
            <div style="width: 400px;">
                <h2 style="text-align:center;">Order Status Distribution</h2>
                <canvas id="orderPieChart"></canvas>
            </div>

            <div style="width: 600px;">
                <h2 style="text-align:center;">Orders per Month</h2>
                <canvas id="ordersLineChart"></canvas>
            </div>
        </div>

    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Pie Chart
const ctxPie = document.getElementById('orderPieChart').getContext('2d');
new Chart(ctxPie, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($statuses); ?>,
        datasets: [{
            label: 'Orders by Status',
            data: <?php echo json_encode($totals); ?>,
            backgroundColor: [
                'rgba(75, 192, 192, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(255, 99, 132, 0.6)',
                'rgba(153, 102, 255, 0.6)'
            ],
            borderWidth: 1
        }]
    },
    options: { responsive: true }
});

// Line Chart - Orders per Month
const ctxLine = document.getElementById('ordersLineChart').getContext('2d');
new Chart(ctxLine, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
            label: 'Orders per Month',
            data: <?php echo json_encode($order_counts); ?>,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>
