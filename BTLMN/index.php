<?php
session_start();
require_once "functions/connect.php";
require_once "functions/helpers.php";

// Nếu chưa đăng nhập thì quay về login
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
	exit();
}

$pageTitle = 'Bảng điều khiển - Quản lý Ký túc xá';
$active = 'home';
include "views/partials/header.php";
?>

<style>
	.hero {
		background: linear-gradient(135deg, #74b9ff, #a29bfe);
		border-radius: 12px;
		color: #fff;
		padding: 28px 24px;
	}
	.stat-icon { font-size: 28px; opacity: .9; }
	.card-quick:hover { transform: translateY(-2px); transition: .15s ease; }
	.table-sm td, .table-sm th { padding: .4rem .6rem; }
</style>

<div class="hero mb-4 shadow-sm">
	<div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
		<div>
			<h2 class="mb-1">Xin chào, <?= htmlspecialchars($_SESSION['full_name'] ?? $_SESSION['username']) ?> 👋</h2>
			<p class="mb-0">Chào mừng bạn đến bảng điều khiển Quản lý Ký túc xá. Dưới đây là tổng quan nhanh và các thao tác thường dùng.</p>
		</div>
		<div class="d-flex gap-2">
			<a class="btn btn-light" href="views/student/create_student.php">+ Thêm sinh viên</a>
			<a class="btn btn-light" href="views/room/create_room.php">+ Thêm phòng</a>
			<a class="btn btn-dark" href="views/registration/create_registration.php">+ Tạo đăng ký</a>
		</div>
	</div>
</div>

<?php renderFlash(); ?>

<div class="row g-3 text-center mb-3">
	<?php
	$totals = [
		['label' => 'Sinh viên', 'sql' => 'SELECT COUNT(*) total FROM sinhvien', 'icon' => '🎓', 'class' => 'primary'],
		['label' => 'Phòng', 'sql' => 'SELECT COUNT(*) total FROM phong', 'icon' => '🛏️', 'class' => 'success'],
		['label' => 'Đăng ký', 'sql' => 'SELECT COUNT(*) total FROM dangky', 'icon' => '📝', 'class' => 'warning']
	];
	foreach ($totals as $t):
		$r = $conn->query($t['sql']);
		$count = $r ? (int)$r->fetch_assoc()['total'] : 0;
	?>
	<div class="col-md-4">
		<div class="card border-0 shadow-sm card-quick">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between">
					<div class="text-start">
						<div class="text-muted small">Tổng <?= $t['label'] ?></div>
						<div class="display-6 mb-0"><?= $count ?></div>
					</div>
					<div class="stat-icon"><?= $t['icon'] ?></div>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>

<div class="row g-3">
	<div class="col-lg-6">
		<div class="card border-0 shadow-sm h-100">
			<div class="card-header bg-white"><strong>Sinh viên mới</strong></div>
			<div class="card-body">
				<?php $sv = $conn->query("SELECT ma_sinh_vien, ho_ten, khoa, lop FROM sinhvien ORDER BY id DESC LIMIT 6"); ?>
				<table class="table table-sm table-hover mb-0">
					<thead><tr><th>Mã SV</th><th>Họ tên</th><th>Khoa/Lớp</th></tr></thead>
					<tbody>
					<?php if ($sv && $sv->num_rows): while($row=$sv->fetch_assoc()): ?>
					<tr>
						<td><?= htmlspecialchars($row['ma_sinh_vien']) ?></td>
						<td><?= htmlspecialchars($row['ho_ten']) ?></td>
						<td><small class="text-muted"><?= htmlspecialchars($row['khoa']) ?> / <?= htmlspecialchars($row['lop']) ?></small></td>
					</tr>
					<?php endwhile; else: ?>
					<tr><td colspan="3" class="text-center text-muted">Chưa có dữ liệu</td></tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="card border-0 shadow-sm h-100">
			<div class="card-header bg-white"><strong>Đăng ký gần đây</strong></div>
			<div class="card-body">
				<?php $reg = $conn->query("SELECT d.id, s.ho_ten, p.ma_phong, d.trang_thai FROM dangky d LEFT JOIN sinhvien s ON d.sinh_vien_id=s.id LEFT JOIN phong p ON d.phong_id=p.id ORDER BY d.created_at DESC LIMIT 6"); ?>
				<table class="table table-sm table-hover mb-0">
					<thead><tr><th>ID</th><th>Sinh viên</th><th>Phòng</th><th>Trạng thái</th></tr></thead>
					<tbody>
					<?php if ($reg && $reg->num_rows): while($row=$reg->fetch_assoc()): ?>
					<tr>
						<td>#<?= $row['id'] ?></td>
						<td><?= htmlspecialchars($row['ho_ten']) ?></td>
						<td><?= htmlspecialchars($row['ma_phong']) ?></td>
						<td><?= getStatusBadge($row['trang_thai']) ?></td>
					</tr>
					<?php endwhile; else: ?>
					<tr><td colspan="4" class="text-center text-muted">Chưa có dữ liệu</td></tr>
					<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

<?php include "views/partials/footer.php"; ?>
