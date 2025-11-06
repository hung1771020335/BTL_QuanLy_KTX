<?php
/**
 * Room Process Handler
 * Xử lý CRUD cho phòng
 */

session_start();
require_once "../functions/connect.php";
require_once "../functions/helpers.php";
requireLogin();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($action == 'create') {
        // Create new room
        $ma_phong = sanitizeInput($_POST['ma_phong'] ?? '');
        $ten_phong = sanitizeInput($_POST['ten_phong'] ?? '');
        $loai_phong = sanitizeInput($_POST['loai_phong'] ?? 'standard');
        $so_giuong = intval($_POST['so_giuong'] ?? 4);
        $gia_thang = floatval($_POST['gia_thang'] ?? 0);
        $tang = intval($_POST['tang'] ?? 1);
        $trang_thai = sanitizeInput($_POST['trang_thai'] ?? 'available');
        $mo_ta = sanitizeInput($_POST['mo_ta'] ?? '');
        
        // Validate
        if (empty($ma_phong) || empty($ten_phong) || $gia_thang <= 0) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc!";
            header("Location: ../views/room/create_room.php");
            exit();
        }
        
        // Check if room code already exists
        $check = $conn->prepare("SELECT id FROM phong WHERE ma_phong = ?");
        $check->bind_param("s", $ma_phong);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $_SESSION['error'] = "Mã phòng đã tồn tại!";
            header("Location: ../views/room/create_room.php");
            exit();
        }
        
        // Insert room
        $stmt = $conn->prepare("INSERT INTO phong (ma_phong, ten_phong, loai_phong, so_giuong, gia_thang, tang, trang_thai, mo_ta) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssidiss", $ma_phong, $ten_phong, $loai_phong, $so_giuong, $gia_thang, $tang, $trang_thai, $mo_ta);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Thêm phòng thành công!";
            header("Location: ../views/room/room.php");
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
            header("Location: ../views/room/create_room.php");
        }
        $stmt->close();
        exit();
        
    } elseif ($action == 'update') {
        // Update room
        $id = $_POST['id'] ?? 0;
        $ma_phong = sanitizeInput($_POST['ma_phong'] ?? '');
        $ten_phong = sanitizeInput($_POST['ten_phong'] ?? '');
        $loai_phong = sanitizeInput($_POST['loai_phong'] ?? 'standard');
        $so_giuong = intval($_POST['so_giuong'] ?? 4);
        $gia_thang = floatval($_POST['gia_thang'] ?? 0);
        $tang = intval($_POST['tang'] ?? 1);
        $trang_thai = sanitizeInput($_POST['trang_thai'] ?? 'available');
        $mo_ta = sanitizeInput($_POST['mo_ta'] ?? '');
        
        $stmt = $conn->prepare("UPDATE phong SET ma_phong=?, ten_phong=?, loai_phong=?, so_giuong=?, gia_thang=?, tang=?, trang_thai=?, mo_ta=? WHERE id=?");
        $stmt->bind_param("sssidissi", $ma_phong, $ten_phong, $loai_phong, $so_giuong, $gia_thang, $tang, $trang_thai, $mo_ta, $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Cập nhật phòng thành công!";
            header("Location: ../views/room/room.php");
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
            header("Location: ../views/room/edit_room.php?id=" . $id);
        }
        $stmt->close();
        exit();
        
    } elseif ($action == 'delete') {
        // Delete room
        $id = $_GET['id'] ?? 0;
        
        $stmt = $conn->prepare("DELETE FROM phong WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $_SESSION['success'] = "Xóa phòng thành công!";
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
        }
        $stmt->close();
        header("Location: ../views/room/room.php");
        exit();
    }
}

header("Location: ../views/room/room.php");
exit();

