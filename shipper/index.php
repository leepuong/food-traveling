<?php include('../config/constants.php'); ?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Shipper Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: sans-serif;
        }

        #map {
            width: 100%;
            height: 100vh;
            float: left;
            transition: width 0.3s;
        }

        .order-list {
            width: 30%;
            height: 100vh;
            overflow-y: auto;
            background: #f9f9f9;
            padding: 10px;
            box-sizing: border-box;
            float: right;
            display: none;
        }

        .order-card {
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }

        .order-card h3 {
            margin: 0 0 10px 0;
        }

        .order-card button {
            padding: 6px 12px;
            margin-right: 10px;
            border: none;
            border-radius: 4px;
            background-color: #2d6cdf;
            color: white;
            cursor: pointer;
        }

        .order-card button.cancel {
            background-color: #e74c3c;
        }

        .toggle-button {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            z-index: 1000;
        }
    </style>
</head>

<body>
    <button class="toggle-button" id="toggleButton">Offline</button>

    <div id="map"></div>

    <div class="order-list" id="orderList">
        <?php
        $sql = "SELECT * FROM tbl_order ORDER BY id DESC";
        $res = mysqli_query($conn, $sql);

        if ($res && mysqli_num_rows($res) > 0) {
            while ($row = mysqli_fetch_assoc($res)) {
                ?>
                <div class="order-card">
                    <h3><?php echo htmlspecialchars($row['customer_name']); ?></h3>
                    <p><?php echo htmlspecialchars($row['customer_contact']); ?></p>
                    <p>
                        Qty: <?php echo isset($row['quantity']) ? (int)$row['quantity'] : 'N/A'; ?> |
                        Total: <?php echo isset($row['total']) ? number_format($row['total'], 0) : '0'; ?>đ
                    </p>
                    <p><i><?php echo htmlspecialchars($row['customer_address']); ?></i></p>
                    <button class="accept" 
                            data-id="<?php echo $row['id']; ?>" 
                            data-address="<?php echo htmlspecialchars($row['customer_address'], ENT_QUOTES); ?>">
                        Nhận
                    </button>
                    <button class="cancel" data-id="<?php echo $row['id']; ?>">Huỷ</button>
                </div>
                <?php
            }
        } else {
            echo "<p>Không có đơn hàng nào.</p>";
        }
        ?>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
    let isOnline = false;

    function initMap() {
        const defaultPos = [16.047079, 108.206230];
        const map = L.map('map').setView(defaultPos, 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const userPos = [position.coords.latitude, position.coords.longitude];
                    L.circleMarker(userPos, {
                        radius: 8,
                        color: 'white',
                        fillColor: '#88e0be',
                        fillOpacity: 1,
                        weight: 2
                    }).addTo(map).bindPopup(
                        `Vị trí của bạn:<br>Lat: ${userPos[0].toFixed(4)}, Lng: ${userPos[1].toFixed(4)}`
                    ).openPopup();

                    map.setView(userPos, 15);
                },
                function () {
                    alert("Không thể lấy vị trí.");
                }
            );
        } else {
            alert("Trình duyệt không hỗ trợ định vị.");
        }
    }

    window.onload = initMap;

    const toggleBtn = document.getElementById('toggleButton');
    const orderList = document.getElementById('orderList');
    const mapDiv = document.getElementById('map');

    toggleBtn.addEventListener('click', function () {
        isOnline = !isOnline;

        if (isOnline) {
            toggleBtn.textContent = 'Online';
            orderList.style.display = 'block';
            mapDiv.style.width = '70%';
        } else {
            toggleBtn.textContent = 'Offline';
            orderList.style.display = 'none';
            mapDiv.style.width = '100%';
        }
    });

    // Xử lý "Nhận" và "Huỷ"
    document.addEventListener('DOMContentLoaded', function () {
        const acceptButtons = document.querySelectorAll('.accept');
        const cancelButtons = document.querySelectorAll('.cancel');

        acceptButtons.forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.dataset.id;
                const customerAddress = this.dataset.address;
                const card = this.closest('.order-card');

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        fetch(`get-estimate.php?lat=${lat}&lng=${lng}&address=${encodeURIComponent(customerAddress)}`)
                            .then(res => res.json())
                            .then(data => {
                                alert(`Đã nhận đơn hàng #${orderId}. Dự kiến đến nơi trong ${data.duration} phút.`);

                                // Cập nhật trạng thái
                                fetch('update-status.php', {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/json' },
                                    body: JSON.stringify({ id: orderId, status: 'On Delivery' })
                                });

                                // Cập nhật nút trạng thái
                                toggleBtn.textContent = `Đang giao đơn hàng #${orderId}`;
                                toggleBtn.disabled = true; // Không cho đổi trạng thái khi đang có đơn
                                if (card) card.remove(); // ẩn đơn vừa nhận khỏi danh sách
                            });
                    });
                } else {
                    alert("Không hỗ trợ định vị.");
                }
            });
        });

        cancelButtons.forEach(button => {
            button.addEventListener('click', function () {
                const orderId = this.dataset.id;
                const card = this.closest('.order-card');

                if (confirm("Bạn chắc chắn muốn huỷ đơn hàng này?")) {
                    fetch('update-status.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ id: orderId, status: 'Cancelled' })
                    }).then(() => {
                        alert("Đơn hàng đã bị huỷ.");
                        if (card) card.remove();
                    });
                }
            });
        });
    });
</script>

</body>
</html>
