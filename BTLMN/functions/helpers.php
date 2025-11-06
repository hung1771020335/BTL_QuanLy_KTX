<?php
/**
 * Helper Functions
 * Các hàm tiện ích hỗ trợ
 */

/**
 * Check if user is logged in
 */
function isLoggedIn() {
	return isset($_SESSION['username']);
}

/**
 * Redirect to login if not logged in
 */
function requireLogin() {
	if (!isLoggedIn()) {
		header("Location: ../login.php");
		exit();
	}
}

/**
 * Sanitize input data
 */
function sanitizeInput($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

/**
 * Format date for display
 */
function formatDate($date, $format = 'd/m/Y') {
	if (empty($date) || $date == '0000-00-00') {
		return '';
	}
	return date($format, strtotime($date));
}

/**
 * Format currency (VND)
 */
function formatCurrency($amount) {
	return number_format($amount, 0, ',', '.') . ' đ';
}

/**
 * Get status badge HTML
 */
function getStatusBadge($status, $type = 'default') {
	$badges = [
		'default' => [
			'pending' => '<span class="badge bg-warning">Chờ xử lý</span>',
			'approved' => '<span class="badge bg-info">Đã duyệt</span>',
			'active' => '<span class="badge bg-success">Đang hoạt động</span>',
			'completed' => '<span class="badge bg-secondary">Hoàn thành</span>',
			'cancelled' => '<span class="badge bg-danger">Đã hủy</span>',
			'available' => '<span class="badge bg-success">Còn trống</span>',
			'occupied' => '<span class="badge bg-danger">Đã đầy</span>',
			'maintenance' => '<span class="badge bg-warning">Bảo trì</span>'
		]
	];
	
	return isset($badges[$type][$status]) ? $badges[$type][$status] : '<span class="badge bg-secondary">' . $status . '</span>';
}

/**
 * Validate email
 */
function isValidEmail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validate phone number (Vietnamese format)
 */
function isValidPhone($phone) {
	$phone = preg_replace('/[^0-9]/', '', $phone);
	return preg_match('/^(0|\+84)[0-9]{9,10}$/', $phone);
}

/**
 * Show alert message
 */
function showAlert($message, $type = 'info') {
	$alertClass = [
		'success' => 'alert-success',
		'error' => 'alert-danger',
		'warning' => 'alert-warning',
		'info' => 'alert-info'
	];
	
	$class = isset($alertClass[$type]) ? $alertClass[$type] : 'alert-info';
	
	return '<div class="alert ' . $class . ' alert-dismissible fade show" role="alert">
				' . htmlspecialchars($message) . '
				<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
			</div>';
}

/**
 * Render and clear session flash messages
 */
function renderFlash() {
	if (isset($_SESSION['success'])) {
		echo showAlert($_SESSION['success'], 'success');
		unset($_SESSION['success']);
	}
	if (isset($_SESSION['error'])) {
		echo showAlert($_SESSION['error'], 'error');
		unset($_SESSION['error']);
	}
	if (isset($_SESSION['warning'])) {
		echo showAlert($_SESSION['warning'], 'warning');
		unset($_SESSION['warning']);
	}
}

