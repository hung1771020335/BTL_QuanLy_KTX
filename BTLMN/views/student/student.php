<?php
/**
 * Student List View
 * Danh sách sinh viên
 */

session_start();
require_once "../../functions/connect.php";
require_once "../../functions/helpers.php";
requireLogin();

// Handle delete
if (isset($_GET['delete'])) {
	header("Location: ../../handle/student_process.php?action=delete&id=" . $_GET['delete']);
	exit();
}

// Get all students
$result = $conn->query("SELECT * FROM sinhvien ORDER BY id DESC");

$pageTitle = 'Quản lý Sinh viên - Ký túc xá';
$active = 'student';
include "../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
	<h2>Danh sách Sinh viên</h2>
	<a href="create_student.php" class="btn btn-success">+ Thêm sinh viên</a>
</div>

<?php renderFlash(); ?>

<div class="row g-2 mb-3">
	<div class="col-md-6">
		<input id="searchInput" type="text" class="form-control" placeholder="Tìm kiếm theo Mã SV, Họ tên, Khoa, Lớp...">
	</div>
	<div class="col-md-6 text-md-end small text-muted align-self-center">
		<?php $count = $conn->query("SELECT COUNT(*) c FROM sinhvien")->fetch_assoc()['c']; ?>
		Tổng số: <strong><?= (int)$count ?></strong> sinh viên
	</div>
</div>

<div class="table-responsive">
	<table id="dataTable" class="table table-striped table-hover align-middle shadow-sm">
		<thead class="table-dark">
			<tr>
				<th>ID</th>
				<th>Mã SV</th>
				<th>Họ tên</th>
				<th>Giới tính</th>
				<th>Ngày sinh</th>
				<th>SĐT</th>
				<th>Email</th>
				<th>Khoa</th>
				<th>Lớp</th>
				<th>Thao tác</th>
			</tr>
		</thead>
		<tbody>
			<?php if ($result->num_rows > 0): ?>
				<?php while ($row = $result->fetch_assoc()): ?>
					<tr>
						<td><?= $row['id'] ?></td>
						<td><?= htmlspecialchars($row['ma_sinh_vien']) ?></td>
						<td><?= htmlspecialchars($row['ho_ten']) ?></td>
						<td><?= htmlspecialchars($row['gioi_tinh']) ?></td>
						<td><?= formatDate($row['ngay_sinh']) ?></td>
						<td><?= htmlspecialchars($row['so_dien_thoai']) ?></td>
						<td><?= htmlspecialchars($row['email']) ?></td>
						<td><?= htmlspecialchars($row['khoa']) ?></td>
						<td><?= htmlspecialchars($row['lop']) ?></td>
						<td>
							<a href="edit_student.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
							<a href="student.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')">Xóa</a>
						</td>
					</tr>
				<?php endwhile; ?>
			<?php else: ?>
				<tr>
					<td colspan="10" class="text-center">Chưa có sinh viên nào</td>
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

