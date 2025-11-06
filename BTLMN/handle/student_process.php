<?php
/**
 * Student Process Handler
 * Xử lý CRUD cho sinh viên
 */

session_start();
require_once "../functions/connect.php";
require_once "../functions/helpers.php";
requireLogin();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($action == 'create') {
        // Create new student
        $ma_sinh_vien = sanitizeInput($_POST['ma_sinh_vien'] ?? '');
        $ho_ten = sanitizeInput($_POST['ho_ten'] ?? '');
        $gioi_tinh = sanitizeInput($_POST['gioi_tinh'] ?? '');
        $ngay_sinh = $_POST['ngay_sinh'] ?? '';
        $so_dien_thoai = sanitizeInput($_POST['so_dien_thoai'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $dia_chi = sanitizeInput($_POST['dia_chi'] ?? '');
        $khoa = sanitizeInput($_POST['khoa'] ?? '');
        $lop = sanitizeInput($_POST['lop'] ?? '');
        $cccd = sanitizeInput($_POST['cccd'] ?? '');
        
        // Validate required fields
        if (empty($ma_sinh_vien) || empty($ho_ten) || empty($ngay_sinh)) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc!";
            header("Location: ../views/student/create_student.php");
            exit();
        }
        
        // Check if student code already exists
        $check = $conn->prepare("SELECT id FROM sinhvien WHERE ma_sinh_vien = ?");
        $check->bind_param("s", $ma_sinh_vien);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $_SESSION['error'] = "Mã sinh viên đã tồn tại!";
            header("Location: ../views/student/create_student.php");
            exit();
        }
        
        // Insert student
        $stmt = $conn->prepare("INSERT INTO sinhvien (ma_sinh_vien, ho_ten, gioi_tinh, ngay_sinh, so_dien_thoai, email, dia_chi, khoa, lop, cccd) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssss", $ma_sinh_vien, $ho_ten, $gioi_tinh, $ngay_sinh, $so_dien_thoai, $email, $dia_chi, $khoa, $lop, $cccd);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Thêm sinh viên thành công!";
            header("Location: ../views/student/student.php");
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
            header("Location: ../views/student/create_student.php");
        }
        $stmt->close();
        exit();
        
    } elseif ($action == 'update') {
        // Update student
        $id = $_POST['id'] ?? 0;
        $ma_sinh_vien = sanitizeInput($_POST['ma_sinh_vien'] ?? '');
        $ho_ten = sanitizeInput($_POST['ho_ten'] ?? '');
        $gioi_tinh = sanitizeInput($_POST['gioi_tinh'] ?? '');
        $ngay_sinh = $_POST['ngay_sinh'] ?? '';
        $so_dien_thoai = sanitizeInput($_POST['so_dien_thoai'] ?? '');
        $email = sanitizeInput($_POST['email'] ?? '');
        $dia_chi = sanitizeInput($_POST['dia_chi'] ?? '');
        $khoa = sanitizeInput($_POST['khoa'] ?? '');
        $lop = sanitizeInput($_POST['lop'] ?? '');
        $cccd = sanitizeInput($_POST['cccd'] ?? '');
        
        $stmt = $conn->prepare("UPDATE sinhvien SET ma_sinh_vien=?, ho_ten=?, gioi_tinh=?, ngay_sinh=?, so_dien_thoai=?, email=?, dia_chi=?, khoa=?, lop=?, cccd=? WHERE id=?");
        $stmt->bind_param("ssssssssssi", $ma_sinh_vien, $ho_ten, $gioi_tinh, $ngay_sinh, $so_dien_thoai, $email, $dia_chi, $khoa, $lop, $cccd, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Cập nhật sinh viên thành công!";
            header("Location: ../views/student/student.php");
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
            header("Location: ../views/student/edit_student.php?id=" . $id);
        }
        $stmt->close();
        exit();
        
    } elseif ($action == 'delete') {
        // Delete student
        $id = $_GET['id'] ?? 0;
        
        $stmt = $conn->prepare("DELETE FROM sinhvien WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Xóa sinh viên thành công!";
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
        }
        $stmt->close();
        header("Location: ../views/student/student.php");
        exit();
    }
}

header("Location: ../views/student/student.php");
exit();

