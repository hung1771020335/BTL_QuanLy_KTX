<?php
/**
 * Edit Registration View
 * Sửa thông tin đăng ký
 */

session_start();
require_once "../../functions/connect.php";
require_once "../../functions/helpers.php";
requireLogin();

$id = $_GET['id'] ?? 0;

// Get registration data
$stmt = $conn->prepare("SELECT * FROM dangky WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $_SESSION['error'] = "Không tìm thấy đăng ký!";
    header("Location: registration.php");
    exit();
}

$registration = $result->fetch_assoc();
$stmt->close();

// Get students and rooms for dropdown
$students = $conn->query("SELECT id, ma_sinh_vien, ho_ten FROM sinhvien ORDER BY ho_ten");
$rooms = $conn->query("SELECT id, ma_phong, ten_phong, trang_thai FROM phong ORDER BY ma_phong");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Đăng ký - Ký túc xá</title>
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
                        <h4>Sửa thông tin Đăng ký</h4>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" action="../../handle/registration_process.php">
                            <input type="hidden" name="action" value="update">
                            <input type="hidden" name="id" value="<?= $registration['id'] ?>">
                            
                            <div class="mb-3">
                                <label class="form-label">Sinh viên <span class="text-danger">*</span></label>
                                <select name="sinh_vien_id" class="form-select" required>
                                    <option value="">-- Chọn sinh viên --</option>
                                    <?php while ($student = $students->fetch_assoc()): ?>
                                        <option value="<?= $student['id'] ?>" <?= $registration['sinh_vien_id'] == $student['id'] ? 'selected' : '' ?>>
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
                                        <option value="<?= $room['id'] ?>" <?= $registration['phong_id'] == $room['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($room['ma_phong']) ?> - <?= htmlspecialchars($room['ten_phong']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ngày đăng ký</label>
                                    <input type="date" name="ngay_dang_ky" class="form-control" value="<?= $registration['ngay_dang_ky'] ?>">
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                                    <input type="date" name="ngay_bat_dau" class="form-control" value="<?= $registration['ngay_bat_dau'] ?>" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ngày kết thúc</label>
                                    <input type="date" name="ngay_ket_thuc" class="form-control" value="<?= $registration['ngay_ket_thuc'] ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                <select name="trang_thai" class="form-select" required>
                                    <option value="pending" <?= $registration['trang_thai'] == 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                    <option value="approved" <?= $registration['trang_thai'] == 'approved' ? 'selected' : '' ?>>Đã duyệt</option>
                                    <option value="active" <?= $registration['trang_thai'] == 'active' ? 'selected' : '' ?>>Đang hoạt động</option>
                                    <option value="completed" <?= $registration['trang_thai'] == 'completed' ? 'selected' : '' ?>>Hoàn thành</option>
                                    <option value="cancelled" <?= $registration['trang_thai'] == 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="ghi_chu" class="form-control" rows="3"><?= htmlspecialchars($registration['ghi_chu']) ?></textarea>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
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

