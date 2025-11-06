📖 1. Giới thiệu
Hệ thống Quản lý Đoàn viên trong trường Đại học được xây dựng nhằm hỗ trợ công tác quản lý, theo dõi và đánh giá hoạt động của Đoàn Thanh niên trong môi trường giáo dục đại học. Thay vì quản lý thủ công bằng giấy tờ hay các tệp Excel rời rạc, hệ thống mang đến một giải pháp tập trung, hiện đại và dễ sử dụng.

🔧 2. Các công nghệ được sử dụng
Hệ điều hành
macOS Windows Ubuntu

Công nghệ chính
PHP HTML5 CSS SCSS JavaScript Bootstrap

Web Server & Database
Apache MySQL XAMPP

Database Management Tools
MySQL Workbench

🚀 3. Hình ảnh các chức năng
Trang đăng nhập
image ### Trang dashboard admin image ### Trang dashboard cán bộ image ### Trang dashboard đoàn viên image ### Trang quản lý liên chi đoàn image ### Trang quản lý chi đoàn image ### Trang quản lý đoàn viên image ### Trang quản lý lịch sử tham gia image ### Trang quản lý đoàn phí image ### Trang quản lý điểm rèn luyện image ### Trang quản lý sự kiện image ### Trang quản lý khen thưởng image ### Trang quản lý thông báo image ### Trang quản lý tài khoản image ## ⚙️ 4. Cài đặt
4.1. Cài đặt công cụ, môi trường và các thư viện cần thiết
Tải và cài đặt XAMPP
👉 https://www.apachefriends.org/download.html
(Khuyến nghị bản XAMPP với PHP 8.x)

Cài đặt Visual Studio Code và các extension:

PHP Intelephense
MySQL
Prettier – Code Formatter
4.2. Tải project
Clone project về thư mục htdocs của XAMPP (ví dụ ổ C):

cd C:\xampp\htdocs
https://github.com/tyanzuq2811/BTL_Quan_ly_doan_vien.git
Truy cập project qua đường dẫn:
👉 http://localhost/authentication_login.
4.3. Setup database
Mở XAMPP Control Panel, Start Apache và MySQL

Truy cập MySQL WorkBench Tạo database:

CREATE DATABASE IF NOT EXISTS quan_ly_doan_vien
   CHARACTER SET utf8mb4
   COLLATE utf8mb4_unicode_ci;
4.4. Setup tham số kết nối
Mở file config.php (hoặc .env) trong project, chỉnh thông tin DB:

<?php
    function getDbConnection() {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "quan_ly_doan_vien";
        $port = 3306;
        $conn = mysqli_connect($servername, $username, $password, $dbname, $port);
        if (!$conn) {
            die("Kết nối database thất bại: " . mysqli_connect_error());
        }
        mysqli_set_charset($conn, "utf8");
        return $conn;
    }
?>
4.5. Chạy hệ thống
Mở XAMPP Control Panel → Start Apache và MySQL

Truy cập hệ thống: 👉 http://localhost/index.php

4.6. Đăng nhập lần đầu
Hệ thống có thể cấp tài khoản admin

Sau khi đăng nhập Admin có thể:

Tạo thông tin tổ chức đoàn (Đoàn trường, Liên chi, Chi đoàn)

Thêm đoàn viên và cấp tài khoản

Quản lý phân quyền theo cấp
