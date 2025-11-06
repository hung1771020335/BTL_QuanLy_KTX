<?php
/**
 * Room List View
 * Danh sách phòng
 */

session_start();
require_once "../../functions/connect.php";
require_once "../../functions/helpers.php";
requireLogin();

// Handle delete
if (isset($_GET['delete'])) {
	header("Location: ../../handle/room_process.php?action=delete&id=" . $_GET['delete']);
	exit();
}

// Get all rooms
$result = $conn->query("SELECT * FROM phong ORDER BY tang, ma_phong");

$pageTitle = 'Quản lý Phòng - Ký túc xá';
$active = 'room';
include "../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
	<h2>Danh sách Phòng</h2>
	<a href="create_room.php" class="btn btn-success">+ Thêm phòng</a>
</div>

<?php renderFlash(); ?>

<div class="row g-2 mb-3">
	<div class="col-md-6">
		<input id="searchInput" type="text" class="form-control" placeholder="Tìm kiếm theo Mã phòng, Tên phòng...">
	</div>
	<div class="col-md-6 text-md-end small text-muted align-self-center">
		<?php $count = $conn->query("SELECT COUNT(*) c FROM phong")->fetch_assoc()['c']; ?>
		Tổng số: <strong><?= (int)$count ?></strong> phòng
	</div>
</div>

<div class="table-responsive">
	<table id="dataTable" class="table table-striped table-hover align-middle shadow-sm">
		<thead class="table-dark">
			<tr>
				<th>ID</th>
				<th>Mã phòng</th>
				<th>Tên phòng</th>
				<th>Loại</th>
				<th>Số giường</th>
				<th>Giá/tháng</th>
				<th>Tầng</th>
				<th>Trạng thái</th>
				<th>Thao tác</th>
			</tr>
		</thead>
		<tbody>
			<?php if ($result->num_rows > 0): ?>
				<?php while ($row = $result->fetch_assoc()): ?>
					<tr>
						<td><?= $row['id'] ?></td>
						<td><?= htmlspecialchars($row['ma_phong']) ?></td>
						<td><?= htmlspecialchars($row['ten_phong']) ?></td>
						<td>
							<?php
							$loai = ['standard' => 'Tiêu chuẩn', 'premium' => 'Cao cấp', 'vip' => 'VIP'];
							echo $loai[$row['loai_phong']] ?? $row['loai_phong'];
							?>
						</td>
						<td><?= $row['so_giuong'] ?></td>
						<td><?= formatCurrency($row['gia_thang']) ?></td>
						<td><?= $row['tang'] ?></td>
						<td><?= getStatusBadge($row['trang_thai']) ?></td>
						<td>
							<a href="edit_room.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
							<a href="room.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa phòng này?')">Xóa</a>
						</td>
					</tr>
				<?php endwhile; ?>
			<?php else: ?>
				<tr>
					<td colspan="9" class="text-center">Chưa có phòng nào</td>
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

