<?php
session_start();
include('../config/constants.php');
?>

<link rel="stylesheet" href="../css/shipper.css">

<button id="statusBtn">Loading...</button>

<?php
$status = $_GET['status'] ?? null;

if ($status === 'online') {
    if (isset($_SESSION['current_order'])) {
        include('order.php');
    } 
}
?>

<script>
    const btnStatus = document.getElementById("statusBtn");

    // Lấy giá trị 'status' từ URL
    const params = new URLSearchParams(window.location.search);
    const status = params.get("status");

    // Gán trạng thái ban đầu của nút
    if (status === "online") {
        btnStatus.classList.add("online");
        btnStatus.textContent = "Online";
    } else {
        btnStatus.classList.add("offline");
        btnStatus.textContent = "Offline";

    }

    btnStatus.addEventListener("click", () => {
        if (btnStatus.classList.contains("offline")) {
            window.location.href = window.location.pathname + '?status=online';
        } else {
            window.location.href = window.location.pathname + '?status=offline';
        }
    });
</script>