<?php
if (session_status() == PHP_SESSION_NONE) session_start();
include('config/constants.php');

if (!isset($_SESSION['user_id']) && !isset($_SESSION['user'])) {
    header("Location: " . SITEURL . "login.php");
    exit;
}

if (isset($_SESSION['user_id'])) {
    $customer_id = intval($_SESSION['user_id']);
    $sql = "SELECT id, order_date, status, total FROM tbl_order WHERE customer_id = ? ORDER BY order_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
} else {
    $customer_email = $_SESSION['user'];
    $sql = "SELECT id, order_date, status, total FROM tbl_order WHERE customer_email = ? ORDER BY order_date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $customer_email);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<?php include('partials-front/menu.php'); ?>
<?php include('partials-front/rightMenu.php'); ?>
<?php include('partials-front/searchBar.php'); ?>

<style>
.container { padding: 24px 40px; min-height: 60vh; }
.orders-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    margin-top: 20px;
    font-family: 'Segoe UI', sans-serif;
}
.orders-table th { background-color: #ff5c8a; color: white; padding: 14px; text-align:left; }
.orders-table td { padding: 14px; border-bottom: 1px solid #f0f0f0; }
.status-badge { font-weight: bold; padding: 5px 12px; border-radius: 6px; display:inline-block; }
.status-pending { background-color: #ffe58f; color: #8c6d1f; }
.status-ordered { background-color: #ffd591; color: #874d00; }
.status-completed { background-color: #b7eb8f; color: #135200; }
.status-cancelled { background-color: #ffa39e; color: #a8071a; }
</style>

<div class="container">
    <h2>My Orders</h2>
    <table class="orders-table" role="table" aria-label="My Orders">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Date</th>
                <th>Status</th>
                <th style="text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): 
                $statusClass = '';
                switch ($row['status']) {
                    case 'Pending': $statusClass = 'status-pending'; break;
                    case 'Ordered': $statusClass = 'status-ordered'; break;
                    case 'Completed': $statusClass = 'status-completed'; break;
                    case 'Cancelled': $statusClass = 'status-cancelled'; break;
                }
            ?>
            <tr>
                <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo date("d/m/Y", strtotime($row['order_date'])); ?></td>
                <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                <td style="text-align:right;"><?php echo number_format($row['total'], 2); ?>$</td>
            </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4" style="text-align:center; color:gray;">No orders found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- <?php include('partials-front/footer.php'); ?> -->

<script>
document.addEventListener("DOMContentLoaded", function(){
    var anchors = document.querySelectorAll("a");
    anchors.forEach(function(a){
        var href = a.getAttribute("href");
        if (!href) return;
        if (href.indexOf('my-orders.php') !== -1) {
            a.classList.add('menu-item');
            a.classList.add('active');
        }
    });
});
</script>
