<?php
/**
 * Create Registration View
 * Thêm đăng ký mới
 */

session_start();
require_once "../../functions/connect.php";
require_once "../../functions/helpers.php";
requireLogin();

// Get students and rooms for dropdown
$students = $conn->query("SELECT id, ma_sinh_vien, ho_ten FROM sinhvien ORDER BY ho_ten");
$rooms = $conn->query("SELECT id, ma_phong, ten_phong, trang_thai FROM phong WHERE trang_thai = 'available' ORDER BY ma_phong");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Đăng ký - Ký túc xá</title>
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
                <li class="nav-item"><a href="../room/room.php" class="nav-link">Phòng ở</a></li>
                <li class="nav-item"><a href="registration.php" class="nav-link active">Đăng ký</a></li>
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
                        <h4>Thêm Đăng ký mới</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="../../handle/registration_process.php">
                            <input type="hidden" name="action" value="create">
                            
                            <div class="mb-3">
                                <label class="form-label">Sinh viên <span class="text-danger">*</span></label>
                                <select name="sinh_vien_id" class="form-select" required>
                                    <option value="">-- Chọn sinh viên --</option>
                                    <?php while ($student = $students->fetch_assoc()): ?>
                                        <option value="<?= $student['id'] ?>">
                                            <?= htmlspecialchars($student['ho_ten']) ?> - <?= htmlspecialchars($student['ma_sinh_vien']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phòng <span class="text-danger">*</span></label>
                                <select name="phong_id" class="form-select" required>
                                    <option value="">-- Chọn phòng --</option>
                                    <?php while ($room = $rooms->fetch_assoc()): ?>
                                        <option value="<?= $room['id'] ?>">
                                            <?= htmlspecialchars($room['ma_phong']) ?> - <?= htmlspecialchars($room['ten_phong']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ngày đăng ký</label>
                                    <input type="date" name="ngay_dang_ky" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="date" name="ngay_bat_dau" class="form-control" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ngày kết thúc</label>
                                    <input type="date" name="ngay_ket_thuc" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="trang_thai" class="form-select" required>
                                    <option value="pending">Chờ xử lý</option>
                                    <option value="approved">Đã duyệt</option>
                                    <option value="active">Đang hoạt động</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="ghi_chu" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Thêm đăng ký</button>
                                <a href="registration.php" class="btn btn-secondary">Hủy</a>
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

