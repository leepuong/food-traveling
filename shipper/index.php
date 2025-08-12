<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Shipper Dashboard</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="./partials/js/menu-action.js"></script>
    <style>
        #map {
            width: 100vw;
            height: 100vh;
            min-height: 400px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            height: 100%;
            overflow: hidden;
        }

        .destination-marker {
            background-color: red;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid white;
        }

        /* Ensure container is above map */
        .container {
            z-index: 1000 !important;
        }

        /* Button disabled state */
        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Route line styling */
        .leaflet-interactive {
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Popup styling */
        .leaflet-popup-content {
            font-size: 14px;
            line-height: 1.4;
        }
    </style>
</head>
<body>
    <?php include('partials/menu.php'); ?>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</body>
</html>