<?php
// Expect $pageTitle and $active
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Quản lý Ký túc xá' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= strpos($_SERVER['REQUEST_URI'], '/views/') !== false ? '../../css/style.css' : 'css/style.css' ?>">
</head>
<body style="background-color:#f8f9fa;">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="<?= strpos($_SERVER['REQUEST_URI'], '/views/') !== false ? '../../index.php' : 'index.php' ?>">🏫 Quản lý Ký túc xá</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsMain">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarsMain">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a href="<?= strpos($_SERVER['REQUEST_URI'], '/views/') !== false ? '../../index.php' : 'index.php' ?>" class="nav-link <?= ($active ?? '') === 'home' ? 'active' : '' ?>">Trang chủ</a></li>
        <li class="nav-item"><a href="<?= strpos($_SERVER['REQUEST_URI'], '/views/') !== false ? '../student/student.php' : 'views/student/student.php' ?>" class="nav-link <?= ($active ?? '') === 'student' ? 'active' : '' ?>">Sinh viên</a></li>
        <li class="nav-item"><a href="<?= strpos($_SERVER['REQUEST_URI'], '/views/') !== false ? '../room/room.php' : 'views/room/room.php' ?>" class="nav-link <?= ($active ?? '') === 'room' ? 'active' : '' ?>">Phòng ở</a></li>
        <li class="nav-item"><a href="<?= strpos($_SERVER['REQUEST_URI'], '/views/') !== false ? '../registration/registration.php' : 'views/registration/registration.php' ?>" class="nav-link <?= ($active ?? '') === 'registration' ? 'active' : '' ?>">Đăng ký</a></li>
        <li class="nav-item"><a href="<?= strpos($_SERVER['REQUEST_URI'], '/views/') !== false ? '../payment/payment.php' : 'views/payment/payment.php' ?>" class="nav-link <?= ($active ?? '') === 'payment' ? 'active' : '' ?>">Thanh toán</a></li>
        <li class="nav-item"><a href="<?= strpos($_SERVER['REQUEST_URI'], '/views/') !== false ? '../../logout.php' : 'logout.php' ?>" class="nav-link">Đăng xuất</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4">

