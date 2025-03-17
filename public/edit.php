<?php
session_start();
require_once '../services/SinhVienService.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$sinhVienService = new SinhVienService();
$nganhHocs = $sinhVienService->getAllNganhHoc();

if (!isset($_GET['maSV'])) {
    header("Location: index.php");
    exit();
}

$sinhVien = $sinhVienService->getById($_GET['maSV']);
if (!$sinhVien) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'MaSV' => $_POST['MaSV'],
        'HoTen' => $_POST['HoTen'],
        'GioiTinh' => $_POST['GioiTinh'],
        'NgaySinh' => $_POST['NgaySinh'],
        'Hinh' => $sinhVien['Hinh'],
        'MaNganh' => $_POST['MaNganh']
    ];
    if ($sinhVienService->update($data)) {
        header("Location: index.php");
    } else {
        echo "Sửa sinh viên thất bại!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Sửa sinh viên</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Mã SV:</label>
                <input type="text" name="MaSV" class="form-control" value="<?php echo $sinhVien['MaSV']; ?>" readonly>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Họ Tên:</label>
                <input type="text" name="HoTen" class="form-control" value="<?php echo $sinhVien['HoTen']; ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Giới Tính:</label>
                <select name="GioiTinh" class="form-select">
                    <option value="Nam" <?php if ($sinhVien['GioiTinh'] == 'Nam') echo 'selected'; ?>>Nam</option>
                    <option value="Nữ" <?php if ($sinhVien['GioiTinh'] == 'Nữ') echo 'selected'; ?>>Nữ</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Ngày Sinh:</label>
                <input type="date" name="NgaySinh" class="form-control" value="<?php echo $sinhVien['NgaySinh']; ?>" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Hình hiện tại:</label>
                <?php if ($sinhVien['Hinh'] && file_exists("../assets/images/" . $sinhVien['Hinh'])): ?>
                    <img src="../assets/images/<?php echo urlencode($sinhVien['Hinh']); ?>" class="img-thumbnail" width="100" alt="Hình hiện tại">
                <?php else: ?>
                    <p>Không có hình</p>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Upload hình mới:</label>
                <input type="file" name="Hinh" class="form-control" accept="image/*">
            </div>
            
            <div class="mb-3">
                <label class="form-label">Ngành Học:</label>
                <select name="MaNganh" class="form-select">
                    <?php foreach ($nganhHocs as $nganh): ?>
                        <option value="<?php echo $nganh['MaNganh']; ?>" <?php if ($nganh['MaNganh'] == $sinhVien['MaNganh']) echo 'selected'; ?>>
                            <?php echo $nganh['TenNganh']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Cập nhật</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>