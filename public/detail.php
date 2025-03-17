<?php
session_start();
require_once '../services/SinhVienService.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['maSV'])) {
    header("Location: index.php");
    exit();
}

$sinhVienService = new SinhVienService();
$sinhVien = $sinhVienService->getById($_GET['maSV']);
if (!$sinhVien) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Chi tiết sinh viên</h1>
        <table class="table table-bordered">
            <tr>
                <th>Mã SV</th>
                <td><?php echo $sinhVien['MaSV']; ?></td>
            </tr>
            <tr>
                <th>Họ Tên</th>
                <td><?php echo $sinhVien['HoTen']; ?></td>
            </tr>
            <tr>
                <th>Giới Tính</th>
                <td><?php echo $sinhVien['GioiTinh']; ?></td>
            </tr>
            <tr>
                <th>Ngày Sinh</th>
                <td><?php echo $sinhVien['NgaySinh']; ?></td>
            </tr>
            <tr>
                <th>Ngành Học</th>
                <td><?php echo $sinhVien['TenNganh']; ?></td>
            </tr>
            <tr>
                <th>Hình</th>
                <td>
                    <?php if ($sinhVien['Hinh'] && file_exists("../assets/images/" . $sinhVien['Hinh'])): ?>
                        <img src="../assets/images/<?php echo urlencode($sinhVien['Hinh']); ?>" class="img-thumbnail" width="100" alt="Hình sinh viên">
                    <?php else: ?>
                        Không có hình
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>