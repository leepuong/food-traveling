# Hệ thống Quản lý Đơn hàng Shipper

## Tổng quan

Hệ thống cho phép shipper xem danh sách đơn hàng, chấp nhận đơn hàng và xem chi tiết đơn hàng với giao diện ẩn/hiện động.

## Các file cần thiết

### 1. File chính

- `index.php` - Trang chính của shipper với bản đồ
- `partials/menu.php` - Menu và logic điều hướng
- `partials/order.php` - Hiển thị danh sách đơn hàng
- `partials/orderDetail.php` - Hiển thị chi tiết đơn hàng (backup)

### 2. API Endpoints

- `partials/api/accept_order.php` - API chấp nhận đơn hàng
- `partials/api/get_order_detail.php` - API lấy chi tiết đơn hàng
- `partials/api/test_api.php` - API test kết nối
- `partials/api/test_connection.php` - Test kết nối database

### 3. JavaScript

- `partials/js/menu-action.js` - Logic xử lý sự kiện và API calls

### 4. CSS

- `css/shipper.css` - Styles cho giao diện shipper

### 5. Database

- `config/constants.php` - Cấu hình kết nối database

### 6. Test Files

- `test_system.php` - Test toàn bộ hệ thống
- `test.html` - Test interface riêng biệt

## Cách hoạt động

### 1. Luồng dữ liệu

```
User clicks "Accept"
    ↓
JavaScript calls accept_order.php
    ↓
Database updates order status
    ↓
JavaScript hides order list
    ↓
JavaScript calls get_order_detail.php
    ↓
Display order details
```

### 2. Cấu trúc HTML

```html
<div class="container">
  <!-- Order List Container -->
  <div id="order-list-container">
    <div class="order-box" data-order-id="1">
      <!-- Order information -->
      <button class="accept-order-btn">Accept</button>
    </div>
  </div>

  <!-- Order Detail Container -->
  <div id="order-detail-container" style="display: none;">
    <button id="back-to-orders">← Back to Orders</button>
    <div id="order-detail-content"></div>
  </div>
</div>
```

### 3. JavaScript Events

- **Accept Button Click**: Ẩn order list, gọi API accept, hiển thị order detail
- **Back Button Click**: Ẩn order detail, hiển thị lại order list

### 4. API Responses

- `accept_order.php`: Trả về `{"success": true/false, "message": "..."}`
- `get_order_detail.php`: Trả về `{"success": true/false, "order": {...}}`

## Cách sử dụng

### 1. Truy cập hệ thống

```
http://localhost/FINAL-PROJECT/food-order-website-php/shipper/
```

### 2. Test hệ thống trước

```
http://localhost/FINAL-PROJECT/food-order-website-php/shipper/test_system.php
```

### 3. Chuyển sang chế độ Online

- Nhấn nút "Offline" để chuyển thành "Online"
- Hệ thống sẽ hiển thị danh sách đơn hàng

### 4. Chấp nhận đơn hàng

- Nhấn nút "Accept" trên bất kỳ đơn hàng nào
- Hệ thống sẽ ẩn danh sách và hiển thị chi tiết đơn hàng

### 5. Quay lại danh sách

- Nhấn nút "← Back to Orders" để quay lại danh sách

## Debug và Troubleshooting

### 1. Kiểm tra Console

- Mở Developer Tools (F12)
- Xem tab Console để debug JavaScript
- Xem tab Network để kiểm tra API calls

### 2. Test System

```
http://localhost/FINAL-PROJECT/food-order-website-php/shipper/test_system.php
```

### 3. Test API

```
http://localhost/FINAL-PROJECT/food-order-website-php/shipper/partials/api/test_api.php
```

### 4. Test Database Connection

```
http://localhost/FINAL-PROJECT/food-order-website-php/shipper/partials/api/test_connection.php
```

### 5. Test HTML

```
http://localhost/FINAL-PROJECT/food-order-website-php/shipper/test.html
```

## Cấu trúc Database

### Bảng tbl_order

- `id` - ID đơn hàng
- `food` - Tên món ăn
- `price` - Giá
- `qty` - Số lượng
- `total` - Tổng tiền
- `customer_name` - Tên khách hàng
- `customer_contact` - Số điện thoại
- `customer_address` - Địa chỉ
- `status` - Trạng thái (Ordered, Accepted, Delivered)
- `order_date` - Ngày đặt hàng

## Lưu ý quan trọng

1. **Database Connection**: Đảm bảo file `config/constants.php` có thông tin kết nối đúng
2. **File Permissions**: Đảm bảo các file PHP có quyền đọc/ghi
3. **Error Handling**: Tất cả API đều có error handling và logging
4. **Session Management**: Chỉ gọi `session_start()` một lần trong `menu.php`
5. **Security**: Validate tất cả input từ user

## Các lỗi đã sửa

### 1. Lỗi include constants.php

- **Vấn đề**: Đường dẫn `../../config/constants.php` không đúng
- **Giải pháp**: Sửa đường dẫn trong `menu.php`

### 2. Lỗi session_start() multiple calls

- **Vấn đề**: `session_start()` được gọi nhiều lần
- **Giải pháp**: Chỉ gọi `session_start()` trong `menu.php`, xóa trong `order.php` và `orderDetail.php`

### 3. Lỗi database connection

- **Vấn đề**: Database connection không được thiết lập
- **Giải pháp**: Đảm bảo `constants.php` được include đúng cách

## Mở rộng tính năng

### 1. Thêm tính năng mới

- Mark as Delivered
- Update Status
- Cancel Order
- Real-time notifications

### 2. Cải thiện UI/UX

- Loading indicators
- Better error messages
- Responsive design
- Animations

### 3. Performance

- Caching
- Database optimization
- API rate limiting
