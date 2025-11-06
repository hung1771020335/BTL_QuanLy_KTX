<?php
/**
 * Create Payment View
 * Thêm thanh toán mới
 */

session_start();
require_once "../../functions/connect.php";
require_once "../../functions/helpers.php";
requireLogin();

// Get registrations for dropdown
$registrations = $conn->query("
    SELECT d.id, s.ho_ten, s.ma_sinh_vien, p.ma_phong
    FROM dangky d
    LEFT JOIN sinhvien s ON d.sinh_vien_id = s.id
    LEFT JOIN phong p ON d.phong_id = p.id
    WHERE d.trang_thai IN ('approved', 'active')
    ORDER BY d.id DESC
");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Thanh toán - Ký túc xá</title>
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
                <li class="nav-item"><a href="../registration/registration.php" class="nav-link">Đăng ký</a></li>
                <li class="nav-item"><a href="payment.php" class="nav-link active">Thanh toán</a></li>
                <li class="nav-item"><a href="../../logout.php" class="nav-link">Đăng xuất</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Thêm Thanh toán mới</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="../../handle/payment_process.php">
                            <input type="hidden" name="action" value="create">
                            
                            <div class="mb-3">
                                <label class="form-label">Đăng ký <span class="text-danger">*</span></label>
                                <select name="dang_ky_id" class="form-select" required>
                                    <option value="">-- Chọn đăng ký --</option>
                                    <?php while ($reg = $registrations->fetch_assoc()): ?>
                                        <option value="<?= $reg['id'] ?>">
                                            <?= htmlspecialchars($reg['ho_ten']) ?> (<?= htmlspecialchars($reg['ma_sinh_vien']) ?>) - Phòng <?= htmlspecialchars($reg['ma_phong']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số tiền (VNĐ) <span class="text-danger">*</span></label>
                                    <input type="number" name="so_tien" class="form-control" min="0" step="1000" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Loại thanh toán <span class="text-danger">*</span></label>
                                    <select name="loai_thanh_toan" class="form-select" required>
                                        <option value="deposit">Đặt cọc</option>
                                        <option value="monthly" selected>Hàng tháng</option>
                                        <option value="final">Thanh toán cuối</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngày thanh toán <span class="text-danger">*</span></label>
                                    <input type="date" name="ngay_thanh_toan" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Phương thức <span class="text-danger">*</span></label>
                                    <select name="phuong_thuc" class="form-select" required>
                                        <option value="cash" selected>Tiền mặt</option>
                                        <option value="bank_transfer">Chuyển khoản</option>
                                        <option value="card">Thẻ</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="trang_thai" class="form-select" required>
                                    <option value="pending">Chờ xử lý</option>
                                    <option value="completed" selected>Đã hoàn thành</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="ghi_chu" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Thêm thanh toán</button>
                                <a href="payment.php" class="btn btn-secondary">Hủy</a>
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

