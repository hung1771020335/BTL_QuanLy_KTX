<?php
/**
 * Registration Process Handler
 * Xử lý CRUD cho đăng ký phòng
 */

session_start();
require_once "../functions/connect.php";
require_once "../functions/helpers.php";
requireLogin();

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($action == 'create') {
        // Create new registration
        $sinh_vien_id = intval($_POST['sinh_vien_id'] ?? 0);
        $phong_id = intval($_POST['phong_id'] ?? 0);
        $ngay_dang_ky = $_POST['ngay_dang_ky'] ?? date('Y-m-d');
        $ngay_bat_dau = $_POST['ngay_bat_dau'] ?? '';
        $ngay_ket_thuc = $_POST['ngay_ket_thuc'] ?? null;
        $trang_thai = sanitizeInput($_POST['trang_thai'] ?? 'pending');
        $ghi_chu = sanitizeInput($_POST['ghi_chu'] ?? '');
        
        // Validate
        if (empty($sinh_vien_id) || empty($phong_id) || empty($ngay_bat_dau)) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin bắt buộc!";
            header("Location: ../views/registration/create_registration.php");
            exit();
        }
        
        // Check if room is available
        $check_room = $conn->prepare("SELECT trang_thai FROM phong WHERE id = ?");
        $check_room->bind_param("i", $phong_id);
        $check_room->execute();
        $room_result = $check_room->get_result();
        if ($room_result->num_rows == 0) {
            $_SESSION['error'] = "Phòng không tồn tại!";
            header("Location: ../views/registration/create_registration.php");
            exit();
        }
        
        // Insert registration
        $stmt = $conn->prepare("INSERT INTO dangky (sinh_vien_id, phong_id, ngay_dang_ky, ngay_bat_dau, ngay_ket_thuc, trang_thai, ghi_chu) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisssss", $sinh_vien_id, $phong_id, $ngay_dang_ky, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai, $ghi_chu);
        
        if ($stmt->execute()) {
            // Update room status if approved
            if ($trang_thai == 'approved' || $trang_thai == 'active') {
                $update_room = $conn->prepare("UPDATE phong SET trang_thai = 'occupied' WHERE id = ?");
                $update_room->bind_param("i", $phong_id);
                $update_room->execute();
                $update_room->close();
            }
            
            $_SESSION['success'] = "Thêm đăng ký thành công!";
            header("Location: ../views/registration/registration.php");
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
            header("Location: ../views/registration/create_registration.php");
        }
        $stmt->close();
        exit();
        
    } elseif ($action == 'update') {
        // Update registration
        $id = $_POST['id'] ?? 0;
        $sinh_vien_id = intval($_POST['sinh_vien_id'] ?? 0);
        $phong_id = intval($_POST['phong_id'] ?? 0);
        $ngay_dang_ky = $_POST['ngay_dang_ky'] ?? '';
        $ngay_bat_dau = $_POST['ngay_bat_dau'] ?? '';
        $ngay_ket_thuc = $_POST['ngay_ket_thuc'] ?? null;
        $trang_thai = sanitizeInput($_POST['trang_thai'] ?? 'pending');
        $ghi_chu = sanitizeInput($_POST['ghi_chu'] ?? '');
        
        // Get old room_id to update status
        $old_room = $conn->prepare("SELECT phong_id FROM dangky WHERE id = ?");
        $old_room->bind_param("i", $id);
        $old_room->execute();
        $old_result = $old_room->get_result();
        $old_data = $old_result->fetch_assoc();
        $old_room_id = $old_data['phong_id'] ?? 0;
        
        $stmt = $conn->prepare("UPDATE dangky SET sinh_vien_id=?, phong_id=?, ngay_dang_ky=?, ngay_bat_dau=?, ngay_ket_thuc=?, trang_thai=?, ghi_chu=? WHERE id=?");
        $stmt->bind_param("iisssssi", $sinh_vien_id, $phong_id, $ngay_dang_ky, $ngay_bat_dau, $ngay_ket_thuc, $trang_thai, $ghi_chu, $id);
        
        if ($stmt->execute()) {
            // Update room statuses
            if ($old_room_id != $phong_id) {
                // Free old room
                $free_room = $conn->prepare("UPDATE phong SET trang_thai = 'available' WHERE id = ?");
                $free_room->bind_param("i", $old_room_id);
                $free_room->execute();
                $free_room->close();
            }
            
            // Update new room status
            if ($trang_thai == 'approved' || $trang_thai == 'active') {
                $update_room = $conn->prepare("UPDATE phong SET trang_thai = 'occupied' WHERE id = ?");
                $update_room->bind_param("i", $phong_id);
                $update_room->execute();
                $update_room->close();
            }
            
            $_SESSION['success'] = "Cập nhật đăng ký thành công!";
            header("Location: ../views/registration/registration.php");
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
            header("Location: ../views/registration/edit_registration.php?id=" . $id);
        }
        $stmt->close();
        exit();
        
    } elseif ($action == 'delete') {
        // Delete registration
        $id = $_GET['id'] ?? 0;
        
        // Get room_id before deleting
        $get_room = $conn->prepare("SELECT phong_id FROM dangky WHERE id = ?");
        $get_room->bind_param("i", $id);
        $get_room->execute();
        $room_result = $get_room->get_result();
        $room_data = $room_result->fetch_assoc();
        $room_id = $room_data['phong_id'] ?? 0;
        
        $stmt = $conn->prepare("DELETE FROM dangky WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            // Free the room
            if ($room_id > 0) {
                $free_room = $conn->prepare("UPDATE phong SET trang_thai = 'available' WHERE id = ?");
                $free_room->bind_param("i", $room_id);
                $free_room->execute();
                $free_room->close();
            }
            
            $_SESSION['success'] = "Xóa đăng ký thành công!";
        } else {
            $_SESSION['error'] = "Lỗi: " . $conn->error;
        }
        $stmt->close();
        header("Location: ../views/registration/registration.php");
        exit();
    }
}

header("Location: ../views/registration/registration.php");
exit();

