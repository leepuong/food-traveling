document.addEventListener('DOMContentLoaded', function () {
    // Global variables for map management
    let map = null;
    let userMarker = null;
    let destinationMarker = null;
    let routingControl = null;
    let routeLayer = null;
    let currentOrderAddress = null;
    let currentOrderId = null;
    
    // Tracking variables
    let trackingInterval = null;
    let isTracking = false;
    let currentUserPosition = null;

    // Test API connection first
    console.log('Testing API connection...');
    fetch('partials/api/test_api.php')
        .then(response => response.json())
        .then(data => {
            console.log('API test result:', data);
        })
        .catch(error => {
            console.error('API test failed:', error);
        });

    // Initialize map when DOM is loaded
    initMap();

    // Handle order acceptance
    document.querySelectorAll('.accept-order-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const orderId = this.dataset.orderId;
            console.log('Accepting order:', orderId);

            // First, accept the order via API
            fetch('partials/api/accept_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `order_id=${orderId}&status=Ordered`
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Response text:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('JSON parse error:', e);
                    throw new Error('Invalid JSON response: ' + text);
                }
            })
            .then(data => {
                console.log('Parsed data:', data);
                if (data.success) {
                    // Hide all order boxes
                    document.querySelectorAll('.order-box').forEach(box => {
                        box.style.display = 'none';
                    });

                    // Show order detail container
                    const orderDetailContainer = document.getElementById('order-detail-container');
                    const orderListContainer = document.getElementById('order-list-container');
                    
                    orderListContainer.style.display = 'none';
                    orderDetailContainer.style.display = 'block';

                    // Load order details
                    loadOrderDetail(orderId);
                } else {
                    alert('Error accepting order: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while accepting the order: ' + error.message);
            });
        });
    });

    // Handle back button
    document.getElementById('back-to-orders').addEventListener('click', function() {
        // Clear routing and markers
        clearRoutingAndMarkers();
        
        // Show order list container
        const orderDetailContainer = document.getElementById('order-detail-container');
        const orderListContainer = document.getElementById('order-list-container');
        
        orderDetailContainer.style.display = 'none';
        orderListContainer.style.display = 'block';

        // Show all order boxes again
        document.querySelectorAll('.order-box').forEach(box => {
            box.style.display = 'block';
        });
    });

    // Function to initialize map
    function initMap() {
        // Vị trí mặc định: Đà Nẵng
        const defaultPos = [16.047079, 108.206230];

        // Tạo bản đồ ban đầu
        map = L.map('map').setView(defaultPos, 15);

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
                    userMarker = L.circleMarker(userPos, {
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
                function() {
                    alert("Không thể lấy vị trí. Hãy bật GPS hoặc cho phép trình duyệt truy cập vị trí.");
                }
            );
        } else {
            alert("Trình duyệt không hỗ trợ Geolocation.");
        }
    }

    // Function to update user position
    function updateUserPosition() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const newPos = [position.coords.latitude, position.coords.longitude];
                    currentUserPosition = newPos;
                    
                    // Update user marker position
                    if (userMarker) {
                        userMarker.setLatLng(newPos);
                        userMarker.getPopup().setContent(
                            `Vị trí của bạn:<br>Lat: ${newPos[0].toFixed(4)}, Lng: ${newPos[1].toFixed(4)}<br>Thời gian: ${new Date().toLocaleTimeString()}`
                        );
                    }
                    
                    // If we have a destination, update the route
                    if (currentOrderAddress && destinationMarker) {
                        updateRoute(newPos, currentOrderAddress);
                    }
                    
                    console.log('User position updated:', newPos);
                    
                    // Show position update notification (only if tracking is active)
                    if (isTracking) {
                        showTrackingNotification(`Position updated: ${newPos[0].toFixed(4)}, ${newPos[1].toFixed(4)}`, 'success');
                    }
                },
                function(error) {
                    console.error('Error updating user position:', error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
    }

    // Function to update route based on new user position
    function updateRoute(userPos, destinationAddress) {
        // Remove old route layer
        if (routeLayer) {
            map.removeLayer(routeLayer);
            routeLayer = null;
        }
        
        // Get destination coordinates from existing marker
        const destLatLng = destinationMarker.getLatLng();
        
        // Create new routing using OSRM
        const routingUrl = `https://router.project-osrm.org/route/v1/driving/${userPos[1]},${userPos[0]};${destLatLng.lng},${destLatLng.lat}?overview=full&geometries=geojson`;
        
        fetch(routingUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Routing service unavailable');
                }
                return response.json();
            })
            .then(routeData => {
                if (routeData.routes && routeData.routes.length > 0) {
                    const route = routeData.routes[0];
                    
                    // Add new route to map
                    routeLayer = L.geoJSON(route.geometry, {
                        style: {
                            color: '#3388ff',
                            weight: 6,
                            opacity: 0.8
                        }
                    }).addTo(map);
                    
                    console.log('Route updated successfully');
                }
            })
            .catch(error => {
                console.error('Error updating route:', error);
            });
    }

    // Function to start tracking
    function startTracking() {
        if (!isTracking) {
            isTracking = true;
            console.log('Starting position tracking...');
            
            // Show notification
            showTrackingNotification('Tracking started - Updating position every 20 seconds', 'info');
            
            // Update position immediately
            updateUserPosition();
            
            // Set interval to update position every 20 seconds
            trackingInterval = setInterval(updateUserPosition, 20000);
        }
    }

    // Function to stop tracking
    function stopTracking() {
        if (isTracking && trackingInterval) {
            isTracking = false;
            clearInterval(trackingInterval);
            trackingInterval = null;
            console.log('Position tracking stopped');
            
            // Show notification
            showTrackingNotification('Tracking stopped', 'warning');
        }
    }

    // Function to show tracking notification
    function showTrackingNotification(message, type) {
        // Remove existing notification if any
        const existingNotification = document.getElementById('tracking-notification');
        if (existingNotification) {
            existingNotification.remove();
        }
        
        // Create notification element
        const notification = document.createElement('div');
        notification.id = 'tracking-notification';
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 5px;
            color: white;
            font-weight: bold;
            z-index: 1000;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            max-width: 300px;
            word-wrap: break-word;
        `;
        
        // Set background color based on type
        if (type === 'info') {
            notification.style.backgroundColor = '#17a2b8';
        } else if (type === 'warning') {
            notification.style.backgroundColor = '#ffc107';
            notification.style.color = '#212529';
        } else if (type === 'success') {
            notification.style.backgroundColor = '#28a745';
        } else {
            notification.style.backgroundColor = '#6c757d';
        }
        
        notification.textContent = message;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.remove();
            }
        }, 3000);
    }

    // Function to clear routing and markers
    function clearRoutingAndMarkers() {
        // Stop tracking when clearing
        stopTracking();
        
        if (routingControl) {
            map.removeControl(routingControl);
            routingControl = null;
        }
        if (routeLayer) {
            map.removeLayer(routeLayer);
            routeLayer = null;
        }
        if (destinationMarker) {
            map.removeLayer(destinationMarker);
            destinationMarker = null;
        }
        currentOrderAddress = null;
        currentOrderId = null;
        
        // Reset button state
        const deliveredBtn = document.querySelector('.btn-success');
        if (deliveredBtn) {
            deliveredBtn.textContent = 'Mark as Delivered';
            deliveredBtn.disabled = false;
            deliveredBtn.style.backgroundColor = '#28a745';
        }
    }

    // Function to create routing
    function createRouting(userPos, destinationAddress) {
        // Clear existing routing first
        clearRoutingAndMarkers();

        // Show loading message
        console.log('Creating route to:', destinationAddress);

        // Geocode the destination address
        const nominatimUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(destinationAddress)}&limit=1`;
        
        fetch(nominatimUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Geocoding service unavailable');
                }
                return response.json();
            })
            .then(data => {
                if (data && data.length > 0) {
                    const destination = data[0];
                    const destLat = parseFloat(destination.lat);
                    const destLon = parseFloat(destination.lon);

                    console.log('Found destination coordinates:', destLat, destLon);

                    // Add destination marker
                    destinationMarker = L.marker([destLat, destLon], {
                        icon: L.divIcon({
                            className: 'destination-marker',
                            html: '<div style="background-color: red; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white;"></div>',
                            iconSize: [12, 12],
                            iconAnchor: [6, 6]
                        })
                    }).addTo(map).bindPopup(`Điểm đến: ${destinationAddress}`);

                    // Create routing using OSRM
                    const routingUrl = `https://router.project-osrm.org/route/v1/driving/${userPos[1]},${userPos[0]};${destLon},${destLat}?overview=full&geometries=geojson`;
                    
                    return fetch(routingUrl);
                } else {
                    throw new Error('Không thể tìm thấy địa chỉ. Vui lòng kiểm tra lại địa chỉ.');
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Routing service unavailable');
                }
                return response.json();
            })
            .then(routeData => {
                if (routeData.routes && routeData.routes.length > 0) {
                    const route = routeData.routes[0];
                    
                    // Add route to map and store reference
                    routeLayer = L.geoJSON(route.geometry, {
                        style: {
                            color: '#3388ff',
                            weight: 6,
                            opacity: 0.8
                        }
                    }).addTo(map);

                    // Fit map to show both markers
                    const bounds = L.latLngBounds([userPos, [destinationMarker.getLatLng()]]);
                    map.fitBounds(bounds, { padding: [50, 50] });

                    console.log('Routing created successfully');
                } else {
                    throw new Error('Không thể tạo tuyến đường. Vui lòng thử lại.');
                }
            })
            .catch(error => {
                console.error('Routing error:', error);
                alert('Lỗi: ' + error.message);
                
                // Clear markers if routing failed
                if (destinationMarker) {
                    map.removeLayer(destinationMarker);
                    destinationMarker = null;
                }
            });
    }

    // Function to load order details
    function loadOrderDetail(orderId) {
        console.log('Loading order detail for ID:', orderId);
        
        fetch(`partials/api/get_order_detail.php?order_id=${orderId}`)
            .then(response => {
                console.log('Detail response status:', response.status);
                return response.text();
            })
            .then(text => {
                console.log('Detail response text:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Detail JSON parse error:', e);
                    throw new Error('Invalid JSON response: ' + text);
                }
            })
            .then(data => {
                console.log('Detail parsed data:', data);
                if (data.success) {
                    const order = data.order;
                    const orderDetailContent = document.getElementById('order-detail-content');
                    
                                         orderDetailContent.innerHTML = `
                         <div class="order-box">
                             <div class="order-desc">
                                 <h4>${order.customer_name} - Order Details</h4>
                                 <p class="contact">Contact: ${order.customer_contact}</p>
                                 <p class="food">Food: ${order.food}</p>
                                 <p class="qtyAndPrice">Qty: ${order.qty}, Total: $${order.total}</p>
                                 <p class="address">Address: ${order.customer_address}</p>
                                 <p class="order-date">Order Date: ${order.order_date || 'N/A'}</p>
                                 <p class="status">Status: <span style="color: #28a745; font-weight: bold;">${order.status}</span></p>
                                 
                                 <br>
                                 <div class="btnBox">
                                     <button class="btn btn-success" onclick="markAsDelivered('${order.customer_address}', ${orderId})">Mark as Delivered</button>
                                     
                                     <button class="btn btn-warning">Update Status</button>
                                     <button class="btn btn-danger" onclick="clearRoutingAndMarkers()">Cancel Order</button>
                                 </div>
                             </div>
                         </div>
                     `;
                } else {
                    document.getElementById('order-detail-content').innerHTML = 
                        '<div class="error">Error loading order details: ' + data.message + '</div>';
                }
            })
            .catch(error => {
                console.error('Detail error:', error);
                document.getElementById('order-detail-content').innerHTML = 
                    '<div class="error">An error occurred while loading order details: ' + error.message + '</div>';
            });
    }

    // Global function to clear routing and markers
    window.clearRoutingAndMarkers = function() {
        clearRoutingAndMarkers();
    };

    // Global function to toggle tracking
    window.toggleTracking = function() {
        const trackingBtn = document.getElementById('tracking-btn');
        
        if (isTracking) {
            stopTracking();
            if (trackingBtn) {
                trackingBtn.textContent = 'Start Tracking';
                trackingBtn.className = 'btn btn-info';
            }
        } else {
            startTracking();
            if (trackingBtn) {
                trackingBtn.textContent = 'Stop Tracking';
                trackingBtn.className = 'btn btn-warning';
            }
        }
    };

    // Global function for Mark as Delivered button
    window.markAsDelivered = function(customerAddress, orderId) {
        currentOrderId = orderId;
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const userPos = [position.coords.latitude, position.coords.longitude];
                    currentUserPosition = userPos;
                    currentOrderAddress = customerAddress;
                    
                    // Create new routing (this will clear existing routing first)
                    createRouting(userPos, customerAddress);
                    
                    // Start tracking user position
                    startTracking();
                    
                    // Call API to mark order as delivered
                    fetch('partials/api/mark_delivered.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            order_id: orderId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Order marked as delivered successfully');
                            // Update the button text to show it's been delivered
                            const deliveredBtn = document.querySelector('.btn-success');
                            if (deliveredBtn) {
                                deliveredBtn.textContent = 'Delivered ✓';
                                deliveredBtn.disabled = true;
                                deliveredBtn.style.backgroundColor = '#6c757d';
                            }
                            
                            // Update tracking button to show it's active
                            const trackingBtn = document.getElementById('tracking-btn');
                            if (trackingBtn) {
                                trackingBtn.textContent = 'Stop Tracking';
                                trackingBtn.className = 'btn btn-warning';
                            }
                        } else {
                            console.error('Failed to mark order as delivered:', data.message);
                            alert('Failed to mark order as delivered: ' + data.message);
                        }
                    })
                    .catch(error => {
                        // console.error('Error marking order as delivered:', error);
                        // alert('An error occurred while marking the order as delivered.');
                    });
                },
                function() {
                    alert("Không thể lấy vị trí. Hãy bật GPS hoặc cho phép trình duyệt truy cập vị trí.");
                }
            );
        } else {
            alert("Trình duyệt không hỗ trợ Geolocation.");
        }
    };
});