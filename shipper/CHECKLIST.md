# Checklist Kiểm tra Hệ thống Order Shipper

## ✅ Các file đã tạo/cập nhật

### Core Files

- [x] `index.php` - Trang chính shipper
- [x] `partials/menu.php` - Menu và logic điều hướng
- [x] `partials/order.php` - Hiển thị danh sách đơn hàng
- [x] `partials/orderDetail.php` - Backup order detail

### API Files

- [x] `partials/api/accept_order.php` - API chấp nhận đơn hàng
- [x] `partials/api/get_order_detail.php` - API lấy chi tiết đơn hàng
- [x] `partials/api/test_api.php` - API test kết nối
- [x] `partials/api/test_connection.php` - Test database connection

### JavaScript

- [x] `partials/js/menu-action.js` - Logic xử lý sự kiện

### CSS

- [x] `css/shipper.css` - Styles cho giao diện

### Documentation

- [x] `README_ORDER_SYSTEM.md` - Hướng dẫn chi tiết
- [x] `test.html` - File test riêng biệt

## 🔧 Cấu hình cần thiết

### Database

- [x] File `config/constants.php` có thông tin kết nối đúng
- [x] Database `food-order` tồn tại
- [x] Bảng `tbl_order` có dữ liệu với status='Ordered'

### Server

- [x] XAMPP/WAMP đang chạy
- [x] Apache server hoạt động
- [x] MySQL server hoạt động

## 🧪 Test Cases

### 1. Test Database Connection

```
URL: http://localhost/FINAL-PROJECT/food-order-website-php/shipper/partials/api/test_connection.php
Expected: Hiển thị "Database connection successful!"
```

### 2. Test API Connection

```
URL: http://localhost/FINAL-PROJECT/food-order-website-php/shipper/partials/api/test_api.php
Expected: {"success": true, "message": "API is working!", "timestamp": "..."}
```

### 3. Test HTML Interface

```
URL: http://localhost/FINAL-PROJECT/food-order-website-php/shipper/test.html
Expected:
- Hiển thị order box
- Nhấn Accept → Ẩn order list, hiển thị order detail
- Nhấn Back → Hiển thị lại order list
```

### 4. Test Main System

```
URL: http://localhost/FINAL-PROJECT/food-order-website-php/shipper/
Expected:
- Hiển thị bản đồ
- Nhấn "Offline" → "Online"
- Hiển thị danh sách đơn hàng
- Nhấn "Accept" → Ẩn list, hiển thị detail
- Nhấn "← Back to Orders" → Hiển thị lại list
```

## 🐛 Debug Steps

### Nếu có lỗi JavaScript:

1. Mở Developer Tools (F12)
2. Xem tab Console
3. Kiểm tra Network tab cho API calls
4. Xem error messages

### Nếu có lỗi Database:

1. Kiểm tra file `config/constants.php`
2. Test connection với `test_connection.php`
3. Kiểm tra database có dữ liệu không

### Nếu có lỗi API:

1. Test với `test_api.php`
2. Kiểm tra đường dẫn file
3. Kiểm tra permissions

## 📁 File Structure

```
shipper/
├── index.php
├── test.html
├── README_ORDER_SYSTEM.md
├── CHECKLIST.md
├── partials/
│   ├── menu.php
│   ├── order.php
│   ├── orderDetail.php
│   ├── js/
│   │   └── menu-action.js
│   └── api/
│       ├── accept_order.php
│       ├── get_order_detail.php
│       ├── test_api.php
│       └── test_connection.php
└── css/
    └── shipper.css
```

## 🚀 Deployment Checklist

### Trước khi deploy:

- [ ] Test tất cả API endpoints
- [ ] Kiểm tra database connection
- [ ] Test JavaScript functionality
- [ ] Kiểm tra CSS styling
- [ ] Test trên nhiều trình duyệt
- [ ] Kiểm tra responsive design

### Sau khi deploy:

- [ ] Test lại tất cả tính năng
- [ ] Kiểm tra error logs
- [ ] Monitor performance
- [ ] Backup database

## 🔄 Maintenance

### Hàng ngày:

- [ ] Kiểm tra error logs
- [ ] Monitor database performance
- [ ] Backup dữ liệu

### Hàng tuần:

- [ ] Update security patches
- [ ] Optimize database
- [ ] Review error reports

### Hàng tháng:

- [ ] Full system backup
- [ ] Performance review
- [ ] Security audit
