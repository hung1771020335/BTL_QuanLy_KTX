<?php
/**
 * Payment List View
 * Danh sách thanh toán
 */

session_start();
require_once "../../functions/connect.php";
require_once "../../functions/helpers.php";
requireLogin();

// Handle delete
if (isset($_GET['delete'])) {
	header("Location: ../../handle/payment_process.php?action=delete&id=" . $_GET['delete']);
	exit();
}

// Get all payments with registration info
$result = $conn->query("
    SELECT t.*, d.id as dang_ky_id, s.ho_ten, s.ma_sinh_vien, p.ma_phong
    FROM thanhtoan t
    LEFT JOIN dangky d ON t.dang_ky_id = d.id
    LEFT JOIN sinhvien s ON d.sinh_vien_id = s.id
    LEFT JOIN phong p ON d.phong_id = p.id
    ORDER BY t.created_at DESC
");

$pageTitle = 'Quản lý Thanh toán - Ký túc xá';
$active = 'payment';
include "../partials/header.php";
?>

<div class="d-flex justify-content-between align-items-center mb-3">
	<h2>Danh sách Thanh toán</h2>
	<a href="create_payment.php" class="btn btn-success">+ Thêm thanh toán</a>
</div>

<?php renderFlash(); ?>

<div class="row g-2 mb-3">
	<div class="col-md-6">
		<input id="searchInput" type="text" class="form-control" placeholder="Tìm theo tên SV, mã SV, phòng, số tiền...">
	</div>
	<div class="col-md-6 text-md-end small text-muted align-self-center">
		<?php $count = $conn->query("SELECT COUNT(*) c FROM thanhtoan")->fetch_assoc()['c']; ?>
		Tổng số: <strong><?= (int)$count ?></strong> giao dịch
	</div>
</div>

<div class="table-responsive">
	<table id="dataTable" class="table table-striped table-hover align-middle shadow-sm">
		<thead class="table-dark">
			<tr>
				<th>ID</th>
				<th>Sinh viên</th>
				<th>Phòng</th>
				<th>Số tiền</th>
				<th>Loại</th>
				<th>Ngày thanh toán</th>
				<th>Phương thức</th>
				<th>Trạng thái</th>
				<th>Thao tác</th>
			</tr>
		</thead>
		<tbody>
			<?php if ($result->num_rows > 0): ?>
				<?php 
				$total = 0;
				while ($row = $result->fetch_assoc()): 
					if ($row['trang_thai'] == 'completed') $total += $row['so_tien'];
				?>
					<tr>
						<td><?= $row['id'] ?></td>
						<td>
							<strong><?= htmlspecialchars($row['ho_ten']) ?></strong><br>
							<small class="text-muted"><?= htmlspecialchars($row['ma_sinh_vien']) ?></small>
						</td>
						<td><?= htmlspecialchars($row['ma_phong']) ?></td>
						<td><strong><?= formatCurrency($row['so_tien']) ?></strong></td>
						<td>
							<?php
							$loai = ['deposit' => 'Đặt cọc', 'monthly' => 'Hàng tháng', 'final' => 'Thanh toán cuối'];
							echo $loai[$row['loai_thanh_toan']] ?? $row['loai_thanh_toan'];
							?>
						</td>
						<td><?= formatDate($row['ngay_thanh_toan']) ?></td>
						<td>
							<?php
							$phuong_thuc = ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản', 'card' => 'Thẻ'];
							echo $phuong_thuc[$row['phuong_thuc']] ?? $row['phuong_thuc'];
							?>
						</td>
						<td><?= getStatusBadge($row['trang_thai']) ?></td>
						<td>
							<a href="edit_payment.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Sửa</a>
							<a href="payment.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa thanh toán này?')">Xóa</a>
						</td>
					</tr>
				<?php endwhile; ?>
			<?php else: ?>
				<tr>
					<td colspan="9" class="text-center">Chưa có thanh toán nào</td>
				</tr>
			<?php endif; ?>
		</tbody>
		<?php if (isset($total)): ?>
		<tfoot>
			<tr class="table-info">
				<td colspan="3" class="text-end"><strong>Tổng thanh toán đã hoàn thành:</strong></td>
				<td colspan="6"><strong><?= formatCurrency($total) ?></strong></td>
			</tr>
		</tfoot>
		<?php endif; ?>
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

