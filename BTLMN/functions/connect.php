<?php
/**
 * Database Connection Function
 * Kết nối cơ sở dữ liệu MySQL (KHÔNG tạo database)
 * 
 * IMPORTANT: This connects to an EXISTING database only - it does NOT create a database
 */

// 1) Load configuration (allow overrides via ENV or optional .env file)
if (file_exists(__DIR__ . '/.env')) {
	$lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	foreach ($lines as $line) {
		if (strpos($line, '=') !== false) {
			list($k, $v) = array_map('trim', explode('=', $line, 2));
			if ($k !== '') {
				putenv("$k=$v");
			}
		}
	}
}

// Database configuration - UPDATE THESE TO MATCH YOUR MYSQL SETUP
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : '130605'; // Your MySQL password
$dbName = getenv('DB_NAME') ?: 'ky_tuc_xa'; // Change this to your actual database name

// 2) Kết nối tới MySQL server trước (không chọn DB để báo lỗi chính xác)
try {
	$serverConn = @new mysqli($dbHost, $dbUser, $dbPass);
	if ($serverConn->connect_error) {
		$msg = "❌ Kết nối MySQL thất bại: " . $serverConn->connect_error . "<br><br>";
		$msg .= "<strong>Hãy kiểm tra:</strong><br>";
		if (strpos($serverConn->connect_error, 'Access denied') !== false) {
			$msg .= "• Sai user/password MySQL<br>";
			$msg .= "• Hiện tại đang dùng: User='$dbUser', Password='***'<br>";
			$msg .= "• Cập nhật password trong functions/connect.php (dòng 23) nếu khác<br>";
		} else {
			$msg .= "• MySQL server đang chạy trong XAMPP Control Panel<br>";
			$msg .= "• Host và port đúng (localhost:3306)<br>";
		}
		die($msg);
	}

	// 3) Chọn database có sẵn (KHÔNG tạo mới - chỉ kết nối tới database đã tồn tại)
	if (!$serverConn->select_db($dbName)) {
		// List available databases to help user
		$dbList = [];
		if ($res = $serverConn->query('SHOW DATABASES')) {
			while ($row = $res->fetch_row()) { 
				$dbList[] = $row[0]; 
			}
			$res->close();
		}
		$suggest = empty($dbList) ? '' : ('<br><br><strong>Databases hiện có:</strong> ' . htmlspecialchars(implode(', ', $dbList)) . '<br>Hãy cập nhật tên database trong functions/connect.php (dòng 24)');
		die("❌ Không tìm thấy database '" . htmlspecialchars($dbName) . "'.<br>Database này chưa được tạo hoặc tên không đúng." . $suggest);
	}

	// 4) Có database → dùng kết nối chính với DB đã chọn
	$conn = $serverConn;
	$conn->set_charset('utf8mb4');

} catch (Exception $e) {
	die("❌ Lỗi kết nối: " . $e->getMessage());
}

// 5) Đóng kết nối khi kết thúc request
function closeConnection() {
	global $conn;
	if (isset($conn)) { 
		$conn->close(); 
	}
}
register_shutdown_function('closeConnection');
