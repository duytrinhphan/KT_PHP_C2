<?php
session_start();
require_once '../services/SinhVienService.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$sinhVienService = new SinhVienService();
$sinhViens = $sinhVienService->getAll();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sinh viên</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Danh sách sinh viên</h1>
        
        <div class="mb-3">
            <a href="create.php" class="btn btn-primary me-2">Thêm sinh viên</a>
            <a href="hocphan.php" class="btn btn-secondary me-2">Đăng ký học phần</a>
            <a href="giohang.php" class="btn btn-info me-2">Xem giỏ hàng</a>
            <a href="login.php?logout=true" class="btn btn-danger">Đăng xuất</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Mã SV</th>
                        <th>Họ Tên</th>
                        <th>Giới Tính</th>
                        <th>Ngày Sinh</th>
                        <th>Ngành Học</th>
                        <th>Hình</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sinhViens as $sv): ?>
                    <tr>
                        <td><?php echo $sv['MaSV']; ?></td>
                        <td><?php echo $sv['HoTen']; ?></td>
                        <td><?php echo $sv['GioiTinh']; ?></td>
                        <td><?php echo $sv['NgaySinh']; ?></td>
                        <td><?php echo $sv['TenNganh']; ?></td>
                        <td>
                            <?php if ($sv['Hinh'] && file_exists("../assets/images/" . $sv['Hinh'])): ?>
                                <img src="../assets/images/<?php echo urlencode($sv['Hinh']); ?>" class="img-thumbnail" width="50" alt="Hình sinh viên">
                            <?php else: ?>
                                Không có hình
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="detail.php?maSV=<?php echo $sv['MaSV']; ?>" class="btn btn-sm btn-info">Chi tiết</a>
                            <a href="edit.php?maSV=<?php echo $sv['MaSV']; ?>" class="btn btn-sm btn-warning">Sửa</a>
                            <a href="delete.php?maSV=<?php echo $sv['MaSV']; ?>" 
                               onclick="return confirm('Bạn có chắc muốn xóa?')" 
                               class="btn btn-sm btn-danger">Xóa</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Bootstrap JS (optional, for interactive components) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>