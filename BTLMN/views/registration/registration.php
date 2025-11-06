<?php
/**
 * Registration List View
 * Danh sách đăng ký phòng
 */

session_start();
require_once "../../functions/connect.php";
require_once "../../functions/helpers.php";
requireLogin();

// Handle delete
if (isset($_GET['delete'])) {
	header("Location: ../../handle/registration_process.php?action=delete&id=" . $_GET['delete']);
	exit();
}

// Get all registrations with student and room info
$result = $conn->query("
    SELECT d.*, s.ho_ten, s.ma_sinh_vien, p.ma_phong, p.ten_phong
    FROM dangky d
    LEFT JOIN sinhvien s ON d.sinh_vien_id = s.id
    LEFT JOIN phong p ON d.phong_id = p.id
    ORDER BY d.created_at DESC
");

$pageTitle = 'Quản lý Đăng ký - Ký túc xá';
$active = 'registration';
include "../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
	<h2>Danh sách Đăng ký Phòng</h2>
	<a href="create_registration.php" class="btn btn-success">+ Thêm đăng ký</a>
</div>

<?php renderFlash(); ?>

<div class="row g-2 mb-3">
	<div class="col-md-6">
		<input id="searchInput" type="text" class="form-control" placeholder="Tìm theo tên SV, mã SV, mã phòng...">
	</div>
	<div class="col-md-6 text-md-end small text-muted align-self-center">
		<?php $count = $conn->query("SELECT COUNT(*) c FROM dangky")->fetch_assoc()['c']; ?>
		Tổng số: <strong><?= (int)$count ?></strong> đăng ký
	</div>
</div>

<div class="table-responsive">
	<table id="dataTable" class="table table-striped table-hover align-middle shadow-sm">
		<thead class="table-dark">
			<tr>
				<th>ID</th>
				<th>Sinh viên</th>
				<th>Phòng</th>
				<th>Ngày đăng ký</th>
				<th>Ngày bắt đầu</th>
				<th>Ngày kết thúc</th>
				<th>Trạng thái</th>
				<th>Thao tác</th>
			</tr>
		</thead>
		<tbody>
			<?php if ($result->num_rows > 0): ?>
				<?php while ($row = $result->fetch_assoc()): ?>
					<tr>
						<td><?= $row['id'] ?></td>
						<td>
							<strong><?= htmlspecialchars($row['ho_ten']) ?></strong><br>
							<small class="text-muted"><?= htmlspecialchars($row['ma_sinh_vien']) ?></small>
						</td>
						<td>
							<strong><?= htmlspecialchars($row['ma_phong']) ?></strong><br>
							<small class="text-muted"><?= htmlspecialchars($row['ten_phong']) ?></small>
						</td>
						<td><?= formatDate($row['ngay_dang_ky']) ?></td>
						<td><?= formatDate($row['ngay_bat_dau']) ?></td>
						<td><?= formatDate($row['ngay_ket_thuc']) ?></td>
						<td><?= getStatusBadge($row['trang_thai']) ?></td>
						<td>
							<a href="edit_registration.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
							<a href="registration.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa đăng ký này?')">Xóa</a>
						</td>
					</tr>
				<?php endwhile; ?>
			<?php else: ?>
				<tr>
					<td colspan="8" class="text-center">Chưa có đăng ký nào</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<script>
	document.getElementById('searchInput').addEventListener('input', function() {
		const q = this.value.toLowerCase();
		const rows = document.querySelectorAll('#dataTable tbody tr');
		rows.forEach(r => {
			const text = r.innerText.toLowerCase();
			r.style.display = text.includes(q) ? '' : 'none';
		});
	});
</script>

<?php include "../partials/footer.php"; ?>

