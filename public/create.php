<?php
session_start();
require_once '../services/SinhVienService.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$sinhVienService = new SinhVienService();
$nganhHocs = $sinhVienService->getAllNganhHoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'MaSV' => $_POST['MaSV'],
        'HoTen' => $_POST['HoTen'],
        'GioiTinh' => $_POST['GioiTinh'],
        'NgaySinh' => $_POST['NgaySinh'],
        'Hinh' => '',
        'MaNganh' => $_POST['MaNganh']
    ];
    if ($sinhVienService->create($data)) {
        header("Location: index.php");
    } else {
        echo "Thêm sinh viên thất bại!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm sinh viên</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Thêm sinh viên</h1>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Mã SV:</label>
                <input type="text" name="MaSV" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Họ Tên:</label>
                <input type="text" name="HoTen" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Giới Tính:</label>
                <select name="GioiTinh" class="form-select">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Ngày Sinh:</label>
                <input type="date" name="NgaySinh" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Hình:</label>
                <input type="file" name="Hinh" class="form-control" accept="image/*" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Ngành Học:</label>
                <select name="MaNganh" class="form-select">
                    <?php foreach ($nganhHocs as $nganh): ?>
                        <option value="<?php echo $nganh['MaNganh']; ?>"><?php echo $nganh['TenNganh']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <button type="submit" class="btn btn-primary">Thêm</button>
        </form>
        <a href="index.php" class="btn btn-secondary mt-3">Quay lại</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>