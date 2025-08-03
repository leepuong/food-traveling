<!-- <?php include('partials/menu.php'); ?>
<?php include('partials/order.php'); ?>

<head>
    <meta charset="UTF-8">
    <title>Shipper Dashboard</title>
    <style>
        #map {
            width: 100vw;
            height: 100vh;
            min-height: 400px;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCXb-z9IssLLDWoshlF2DoCXk1mWq5rbHE"></script>
</head>

<body>
    <div id="map"></div>
    <script>
        function initMap() {
            var defaultPos = {
                lat: 16.047079,
                lng: 108.206230
            };
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 15,
                center: defaultPos
            });
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        let userPos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };


                        new google.maps.Marker({
                            position: userPos,
                            map: map,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 8,
                                fillColor: '#4285F4',
                                fillOpacity: 1,
                                strokeWeight: 2,
                                strokeColor: 'white'
                            },
                            title: "Vị trí của bạn, " + userPos.lat.toFixed(4) + ", " + userPos.lng.toFixed(4)
                        });

                        map.setCenter(userPos);
                    },
                    function() {
                        alert("Không thể lấy vị trí. Hãy bật GPS hoặc cho phép trình duyệt truy cập vị trí.");
                    }
                );
            } else {
                alert("Trình duyệt của bạn không hỗ trợ Geolocation.");
            }
        }

        window.onload = initMap;
    </script>
</body>

</html> -->



<?php include('partials/menu.php'); ?>

<head>
    <meta charset="UTF-8">
    <title>Shipper Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map {
            width: 100vw;
            height: 100vh;
            min-height: 400px;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        function initMap() {
            // Vị trí mặc định: Đà Nẵng
            const defaultPos = [16.047079, 108.206230];

            // Tạo bản đồ ban đầu
            const map = L.map('map').setView(defaultPos, 15);

            // Thêm layer bản đồ OSM
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors'
            }).addTo(map);

            // Kiểm tra geolocation
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const userPos = [position.coords.latitude, position.coords.longitude];

                        // Thêm marker màu xanh tại vị trí người dùng
                        const userMarker = L.circleMarker(userPos, {
                            radius: 8,
                            color: 'white',
                            fillColor: '#4285F4',
                            fillOpacity: 1,
                            weight: 2
                        }).addTo(map).bindPopup(
                            `Vị trí của bạn:<br>Lat: ${userPos[0].toFixed(4)}, Lng: ${userPos[1].toFixed(4)}`
                        ).openPopup();

                        map.setView(userPos, 15);
                    },
                    function() {
                        alert("Không thể lấy vị trí. Hãy bật GPS hoặc cho phép trình duyệt truy cập vị trí.");
                    }
                );
            } else {
                alert("Trình duyệt không hỗ trợ Geolocation.");
            }
        }

        window.onload = initMap;
    </script>
</body>

</html>