<?php
session_start();
require_once '../services/HocPhanService.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$hocPhanService = new HocPhanService();
$hocPhans = $hocPhanService->getAll();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['maHP'])) {
    $maHP = $_POST['maHP'];
    if (!in_array($maHP, $_SESSION['cart'])) {
        $hocPhan = $hocPhanService->getById($maHP);
        if ($hocPhan['SoLuongDuKien'] > 0) {
            $_SESSION['cart'][] = $maHP;
        } else {
            echo "<script>alert('Học phần này đã hết chỗ!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký học phần</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Danh sách học phần</h1>
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Mã HP</th>
                        <th>Tên HP</th>
                        <th>Số tín chỉ</th>
                        <th>Số lượng dự kiến</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($hocPhans as $hp): ?>
                    <tr>
                        <td><?php echo $hp['MaHP']; ?></td>
                        <td><?php echo $hp['TenHP']; ?></td>
                        <td><?php echo $hp['SoTinChi']; ?></td>
                        <td><?php echo $hp['SoLuongDuKien']; ?></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="maHP" value="<?php echo $hp['MaHP']; ?>">
                                <button type="submit" class="btn btn-success btn-sm">Thêm vào giỏ</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <a href="giohang.php" class="btn btn-info me-2">Xem giỏ hàng</a>
        <a href="index.php" class="btn btn-secondary">Quay lại</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>