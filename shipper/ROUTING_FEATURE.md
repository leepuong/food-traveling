# Tính năng Routing cho Shipper

## Mô tả

Tính năng này cho phép shipper tạo tuyến đường từ vị trí hiện tại đến địa chỉ khách hàng khi nhấn nút "Mark as Delivered".

## Cách hoạt động

### 1. Khi nhấn "Mark as Delivered"

- Sử dụng HTML5 Geolocation API để lấy vị trí hiện tại của shipper
- Geocode địa chỉ khách hàng thông qua OpenStreetMap Nominatim API
- Tạo tuyến đường từ vị trí shipper đến địa chỉ khách hàng sử dụng OSRM API
- Hiển thị marker xanh (vị trí shipper) và marker đỏ (điểm đến)
- Hiển thị tuyến đường màu xanh dương trên bản đồ
- Tự động điều chỉnh zoom để hiển thị cả hai điểm
- Cập nhật trạng thái đơn hàng thành "Delivered" trong database
- Thay đổi text button thành "Delivered ✓" và disable button

### 2. Khi nhấn "Back to Orders"

- Xóa tất cả markers và tuyến đường khỏi bản đồ
- Reset trạng thái button về "Mark as Delivered"
- Quay lại danh sách đơn hàng

## Các API được sử dụng

### 1. HTML5 Geolocation API

- Lấy vị trí hiện tại của shipper
- Yêu cầu quyền truy cập vị trí từ trình duyệt

### 2. OpenStreetMap Nominatim API

- Geocode địa chỉ khách hàng thành tọa độ
- URL: `https://nominatim.openstreetmap.org/search`

### 3. OSRM (Open Source Routing Machine)

- Tạo tuyến đường từ điểm A đến điểm B
- URL: `https://router.project-osrm.org/route/v1/driving/`

### 4. Custom API

- `mark_delivered.php`: Cập nhật trạng thái đơn hàng thành "Delivered"

## Cấu trúc file

### Files đã cập nhật:

- `index.php`: Cấu trúc HTML và CSS cho bản đồ
- `partials/js/menu-action.js`: Logic JavaScript cho tính năng routing
- `partials/order.php`: Cấu trúc HTML cho danh sách đơn hàng
- `partials/menu.php`: Menu navigation

### Files mới:

- `partials/api/mark_delivered.php`: API endpoint để cập nhật trạng thái đơn hàng

## Yêu cầu hệ thống

### 1. Trình duyệt

- Hỗ trợ HTML5 Geolocation API
- Hỗ trợ Fetch API
- Hỗ trợ ES6+ JavaScript

### 2. Kết nối internet

- Cần kết nối internet để sử dụng các API bên ngoài
- OpenStreetMap Nominatim API
- OSRM Routing API

### 3. Quyền truy cập

- Quyền truy cập vị trí từ trình duyệt
- Quyền truy cập database để cập nhật trạng thái đơn hàng

## Xử lý lỗi

### 1. Không thể lấy vị trí

- Hiển thị thông báo yêu cầu bật GPS hoặc cho phép truy cập vị trí
- Không tạo tuyến đường nếu không có vị trí

### 2. Không tìm thấy địa chỉ

- Hiển thị thông báo lỗi geocoding
- Yêu cầu kiểm tra lại địa chỉ

### 3. Không thể tạo tuyến đường

- Hiển thị thông báo lỗi routing
- Xóa marker đích nếu có

### 4. Lỗi database

- Hiển thị thông báo lỗi cập nhật trạng thái
- Không thay đổi trạng thái button

## Tính năng bảo mật

### 1. Input validation

- Kiểm tra order_id trước khi cập nhật database
- Sử dụng prepared statements để tránh SQL injection

### 2. Error handling

- Xử lý tất cả các trường hợp lỗi có thể xảy ra
- Không hiển thị thông tin nhạy cảm trong error messages

### 3. CORS

- Cấu hình CORS headers cho API endpoints
- Chỉ cho phép POST method cho API cập nhật

## Tối ưu hóa

### 1. Performance

- Sử dụng async/await cho các API calls
- Cache kết quả geocoding nếu có thể
- Tối ưu hóa việc xóa và tạo lại markers

### 2. UX

- Hiển thị loading states khi cần thiết
- Thông báo rõ ràng cho người dùng
- Disable button sau khi đã delivered để tránh duplicate requests

### 3. Memory management

- Xóa markers và routing cũ trước khi tạo mới
- Reset các biến global khi cần thiết
