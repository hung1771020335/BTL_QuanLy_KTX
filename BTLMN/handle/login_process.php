<?php
/**
 * Login Process Handler
 * Xử lý đăng nhập
 */

session_start();
require_once "../functions/connect.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = $_POST['username'] ?? '';
	$password = $_POST['password'] ?? '';
	
	if (empty($username) || empty($password)) {
		$_SESSION['error'] = "Vui lòng nhập đầy đủ thông tin!";
		header("Location: ../login.php");
		exit();
	}
	
	// Prepare statement to prevent SQL injection
	$stmt = $conn->prepare("SELECT id, username, password, full_name, role FROM users WHERE username = ?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$result = $stmt->get_result();
	
	if ($result->num_rows > 0) {
		$user = $result->fetch_assoc();
		$storedHash = $user['password'] ?? '';
		$input = $password;
		$match = false;
		$upgradeToMd5 = false;
		
		// 1) If stored is bcrypt or other password_hash formats
		$info = password_get_info($storedHash);
		if (!empty($storedHash) && ($info['algo'] ?? 0) !== 0) {
			$match = password_verify($input, $storedHash);
		} elseif (preg_match('/^[a-f0-9]{32}$/i', (string)$storedHash)) {
			// 2) Legacy MD5
			$match = (md5($input) === $storedHash);
		} else {
			// 3) Plain text (legacy/incorrectly seeded)
			$match = hash_equals((string)$storedHash, $input);
			$upgradeToMd5 = $match; // upgrade after successful login
		}
		
		if ($match) {
			// Optionally upgrade plain-text password to MD5 for consistency
			if ($upgradeToMd5) {
				$upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
				$newMd5 = md5($input);
				$upd->bind_param("si", $newMd5, $user['id']);
				$upd->execute();
				$upd->close();
			}
			
			// Set session variables
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['username'] = $user['username'];
			$_SESSION['full_name'] = $user['full_name'];
			$_SESSION['role'] = $user['role'];
			
			// Close statement before redirect
			$stmt->close();
			
			// Redirect to dashboard
			header("Location: ../index.php");
			exit();
		} else {
			$_SESSION['error'] = "Sai mật khẩu!";
			
			// Close statement before redirect
			$stmt->close();
			
			header("Location: ../login.php");
			exit();
		}
	} else {
		$_SESSION['error'] = "Tên đăng nhập không tồn tại!";
		
		// Close statement before redirect
		$stmt->close();
		
		header("Location: ../login.php");
		exit();
	}
} else {
	header("Location: ../login.php");
	exit();
}

// Note: Connection is automatically closed by register_shutdown_function in connect.php
