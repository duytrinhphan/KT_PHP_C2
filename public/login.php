<?php
session_start();
require_once '../services/SinhVienService.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $maSV = $_POST['maSV'];
    $sinhVienService = new SinhVienService();
    $sinhVien = $sinhVienService->getById($maSV);
    if ($sinhVien) {
        $_SESSION['user'] = $maSV;
        header("Location: index.php");
    } else {
        echo "<script>alert('Mã sinh viên không tồn tại!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Đăng nhập</h1>
        <form method="POST" class="w-50">
            <div class="mb-3">
                <label class="form-label">Mã SV:</label>
                <input type="text" name="maSV" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Đăng nhập</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>