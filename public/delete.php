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
if ($sinhVienService->delete($_GET['maSV'])) {
    header("Location: index.php");
} else {
    echo "Xóa sinh viên thất bại!";
}
?>