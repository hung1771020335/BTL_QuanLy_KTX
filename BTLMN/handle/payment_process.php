<?php
/**
 * Payment Process Handler
 * Xử lý CRUD cho thanh toán
 */

session_start();
require_once "../functions/connect.php";
require_once "../functions/helpers.php";
requireLogin();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($action == 'create') {
        // Create new payment
        $dang_ky_id = intval($_POST['dang_ky_id'] ?? 0);
        $so_tien = floatval($_POST['so_tien'] ?? 0);
        $loai_thanh_toan = sanitizeInput($_POST['loai_thanh_toan'] ?? 'monthly');
        $ngay_thanh_toan = $_POST['ngay_thanh_toan'] ?? date('Y-m-d');
        $phuong_thuc = sanitizeInput($_POST['phuong_thuc'] ?? 'cash');
        $trang_thai = sanitizeInput($_POST['trang_thai'] ?? 'pending');
        $ghi_chu = sanitizeInput($_POST['ghi_chu'] ?? '');
        
        // Validate
        if (empty($dang_ky_id) || $so_tien <= 0) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc!";
            header("Location: ../views/payment/create_payment.php");
            exit();
        }
        
        // Insert payment
        $stmt = $conn->prepare("INSERT INTO thanhtoan (dang_ky_id, so_tien, loai_thanh_toan, ngay_thanh_toan, phuong_thuc, trang_thai, ghi_chu) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idsssss", $dang_ky_id, $so_tien, $loai_thanh_toan, $ngay_thanh_toan, $phuong_thuc, $trang_thai, $ghi_chu);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Thêm thanh toán thành công!";
            header("Location: ../views/payment/payment.php");
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
            header("Location: ../views/payment/create_payment.php");
        }
        $stmt->close();
        exit();
        
    } elseif ($action == 'update') {
        // Update payment
        $id = $_POST['id'] ?? 0;
        $dang_ky_id = intval($_POST['dang_ky_id'] ?? 0);
        $so_tien = floatval($_POST['so_tien'] ?? 0);
        $loai_thanh_toan = sanitizeInput($_POST['loai_thanh_toan'] ?? 'monthly');
        $ngay_thanh_toan = $_POST['ngay_thanh_toan'] ?? '';
        $phuong_thuc = sanitizeInput($_POST['phuong_thuc'] ?? 'cash');
        $trang_thai = sanitizeInput($_POST['trang_thai'] ?? 'pending');
        $ghi_chu = sanitizeInput($_POST['ghi_chu'] ?? '');
        
        $stmt = $conn->prepare("UPDATE thanhtoan SET dang_ky_id=?, so_tien=?, loai_thanh_toan=?, ngay_thanh_toan=?, phuong_thuc=?, trang_thai=?, ghi_chu=? WHERE id=?");
        $stmt->bind_param("idsssssi", $dang_ky_id, $so_tien, $loai_thanh_toan, $ngay_thanh_toan, $phuong_thuc, $trang_thai, $ghi_chu, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Cập nhật thanh toán thành công!";
            header("Location: ../views/payment/payment.php");
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
            header("Location: ../views/payment/edit_payment.php?id=" . $id);
        }
        $stmt->close();
        exit();
        
    } elseif ($action == 'delete') {
        // Delete payment
        $id = $_GET['id'] ?? 0;
        
        $stmt = $conn->prepare("DELETE FROM thanhtoan WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Xóa thanh toán thành công!";
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
        }
        $stmt->close();
        header("Location: ../views/payment/payment.php");
        exit();
    }
}

header("Location: ../views/payment/payment.php");
exit();

