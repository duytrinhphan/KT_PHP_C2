<?php
session_start();
require_once '../services/DangKyService.php';
require_once '../services/HocPhanService.php';
require_once '../services/SinhVienService.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$dangKyService = new DangKyService();
$hocPhanService = new HocPhanService();
$sinhVienService = new SinhVienService();

$sinhVien = $sinhVienService->getById($_SESSION['user']);
if (!$sinhVien) {
    echo "Không tìm thấy thông tin sinh viên!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save'])) {
    $maSV = $_SESSION['user'];
    if ($dangKyService->saveDangKy($maSV, $_SESSION['cart'])) {
        unset($_SESSION['cart']);
        echo "<script>alert('Đăng ký thành công!'); window.location.href='index.php';</script>";
        exit();
    } else {
        echo "<script>alert('Đăng ký thất bại!');</script>";
    }
}

if (isset($_GET['delete'])) {
    $index = $_GET['delete'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']);
    }
    header("Location: giohang.php");
    exit();
}

if (isset($_GET['delete_all'])) {
    unset($_SESSION['cart']);
    header("Location: giohang.php");
    exit();
}

$dangKyHocPhans = $dangKyService->getDangKyBySinhVien($_SESSION['user']);

$soHocPhan = count($_SESSION['cart']);
$tongSoTinChi = 0;
foreach ($_SESSION['cart'] as $maHP) {
    $hp = $hocPhanService->getById($maHP);
    if ($hp) {
        $tongSoTinChi += $hp['SoTinChi'];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Giỏ hàng</h1>

        <!-- Thông tin sinh viên -->
        <h2 class="mb-3">Thông tin sinh viên</h2>
        <table class="table table-bordered mb-4">
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
        </table>

        <!-- Giỏ hàng -->
        <h2 class="mb-3">Học phần trong giỏ hàng</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Mã HP</th>
                        <th>Tên HP</th>
                        <th>Số tín chỉ</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($_SESSION['cart'] as $index => $maHP) {
                        $hp = $hocPhanService->getById($maHP);
                        if ($hp) {
                            echo "<tr>
                                <td>{$hp['MaHP']}</td>
                                <td>{$hp['TenHP']}</td>
                                <td>{$hp['SoTinChi']}</td>
                                <td>
                                    <a href='giohang.php?delete=$index' class='btn btn-danger btn-sm'>Xóa</a>
                                </td>
                            </tr>";
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Tổng kết -->
        <div class="mb-3">
            <p><strong>Số học phần:</strong> <?php echo $soHocPhan; ?></p>
            <p><strong>Tổng số tín chỉ:</strong> <?php echo $tongSoTinChi; ?></p>
        </div>

        <!-- Nút điều khiển -->
        <form method="POST" class="mb-3">
            <button type="submit" name="save" class="btn btn-primary">Lưu đăng ký</button>
        </form>
        <a href="giohang.php?delete_all=true" class="btn btn-danger me-2">Xóa toàn bộ</a>
        <a href="hocphan.php" class="btn btn-secondary">Quay lại đăng ký</a>

        <!-- Học phần đã đăng ký -->
        <h2 class="mt-4 mb-3">Học phần đã đăng ký</h2>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Mã ĐK</th>
                        <th>Mã HP</th>
                        <th>Tên HP</th>
                        <th>Số tín chỉ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dangKyHocPhans as $dk): ?>
                    <tr>
                        <td><?php echo $dk['MaDK']; ?></td>
                        <td><?php echo $dk['MaHP']; ?></td>
                        <td><?php echo $dk['TenHP']; ?></td>
                        <td><?php echo $dk['SoTinChi']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>