<?php
/**
 * Create Student View
 * Thêm sinh viên mới
 */

session_start();
require_once "../../functions/connect.php";
require_once "../../functions/helpers.php";
requireLogin();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sinh viên - Ký túc xá</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="../../index.php">🏫 Quản lý Ký túc xá</a>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="../../index.php" class="nav-link">Trang chủ</a></li>
                <li class="nav-item"><a href="student.php" class="nav-link active">Sinh viên</a></li>
                <li class="nav-item"><a href="../room/room.php" class="nav-link">Phòng ở</a></li>
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
                        <h4>Thêm Sinh viên mới</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="../../handle/student_process.php">
                            <input type="hidden" name="action" value="create">
                            
                            <div class="mb-3">
                                <label class="form-label">Mã sinh viên <span class="text-danger">*</span></label>
                                <input type="text" name="ma_sinh_vien" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Họ tên <span class="text-danger">*</span></label>
                                <input type="text" name="ho_ten" class="form-control" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Giới tính <span class="text-danger">*</span></label>
                                    <select name="gioi_tinh" class="form-select" required>
                                        <option value="Nam">Nam</option>
                                        <option value="Nữ">Nữ</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Ngày sinh <span class="text-danger">*</span></label>
                                    <input type="date" name="ngay_sinh" class="form-control" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="so_dien_thoai" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <textarea name="dia_chi" class="form-control" rows="2"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Khoa</label>
                                    <input type="text" name="khoa" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Lớp</label>
                                    <input type="text" name="lop" class="form-control">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">CCCD/CMND</label>
                                <input type="text" name="cccd" class="form-control">
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Thêm sinh viên</button>
                                <a href="student.php" class="btn btn-secondary">Hủy</a>
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

