<?php
/**
 * Edit Room View
 * Sửa thông tin phòng
 */

session_start();
require_once "../../functions/connect.php";
require_once "../../functions/helpers.php";
requireLogin();

$id = $_GET['id'] ?? 0;

// Get room data
$stmt = $conn->prepare("SELECT * FROM phong WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Không tìm thấy phòng!";
    header("Location: room.php");
    exit();
}

$room = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Phòng - Ký túc xá</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="../../index.php">🏫 Quản lý Ký túc xá</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="../../index.php" class="nav-link">Trang chủ</a></li>
                <li class="nav-item"><a href="../student/student.php" class="nav-link">Sinh viên</a></li>
                <li class="nav-item"><a href="room.php" class="nav-link active">Phòng ở</a></li>
                <li class="nav-item"><a href="../registration/registration.php" class="nav-link">Đăng ký</a></li>
                <li class="nav-item"><a href="../payment/payment.php" class="nav-link">Thanh toán</a></li>
                <li class="nav-item"><a href="../../logout.php" class="nav-link">Đăng xuất</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Sửa thông tin Phòng</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="../../handle/room_process.php">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?= $room['id'] ?>">
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Mã phòng <span class="text-danger">*</span></label>
                                    <input type="text" name="ma_phong" class="form-control" value="<?= htmlspecialchars($room['ma_phong']) ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tên phòng <span class="text-danger">*</span></label>
                                    <input type="text" name="ten_phong" class="form-control" value="<?= htmlspecialchars($room['ten_phong']) ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Loại phòng <span class="text-danger">*</span></label>
                                    <select name="loai_phong" class="form-select" required>
                                        <option value="standard" <?= $room['loai_phong'] == 'standard' ? 'selected' : '' ?>>Tiêu chuẩn</option>
                                        <option value="premium" <?= $room['loai_phong'] == 'premium' ? 'selected' : '' ?>>Cao cấp</option>
                                        <option value="vip" <?= $room['loai_phong'] == 'vip' ? 'selected' : '' ?>>VIP</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số giường <span class="text-danger">*</span></label>
                                    <input type="number" name="so_giuong" class="form-control" value="<?= $room['so_giuong'] ?>" min="1" max="10" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Giá/tháng (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" name="gia_thang" class="form-control" value="<?= $room['gia_thang'] ?>" min="0" step="1000" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tầng <span class="text-danger">*</span></label>
                                    <input type="number" name="tang" class="form-control" value="<?= $room['tang'] ?>" min="1" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="trang_thai" class="form-select" required>
                                    <option value="available" <?= $room['trang_thai'] == 'available' ? 'selected' : '' ?>>Còn trống</option>
                                    <option value="occupied" <?= $room['trang_thai'] == 'occupied' ? 'selected' : '' ?>>Đã đầy</option>
                                    <option value="maintenance" <?= $room['trang_thai'] == 'maintenance' ? 'selected' : '' ?>>Bảo trì</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="mo_ta" class="form-control" rows="3"><?= htmlspecialchars($room['mo_ta']) ?></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                <a href="room.php" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

