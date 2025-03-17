<?php
require_once '../config/database.php';

class SinhVienService {
    private $db;
    private $uploadDir = '../assets/images/'; // Thư mục lưu hình ảnh

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT sv.*, nh.TenNganh FROM SinhVien sv JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $hinh = $this->uploadImage($_FILES['Hinh']);
        if ($hinh === false) {
            return false;
        }

        $query = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh) 
                 VALUES (:MaSV, :HoTen, :GioiTinh, :NgaySinh, :Hinh, :MaNganh)";
        $stmt = $this->db->prepare($query);
        $data['Hinh'] = $hinh;
        return $stmt->execute($data);
    }

    public function update($data) {
        $hinh = isset($_FILES['Hinh']) && $_FILES['Hinh']['error'] == 0 ? $this->uploadImage($_FILES['Hinh']) : $data['Hinh'];
        if ($hinh === false && !isset($_FILES['Hinh'])) {
            return false;
        }

        $query = "UPDATE SinhVien SET HoTen = :HoTen, GioiTinh = :GioiTinh, NgaySinh = :NgaySinh, 
                 Hinh = :Hinh, MaNganh = :MaNganh WHERE MaSV = :MaSV";
        $stmt = $this->db->prepare($query);
        $data['Hinh'] = $hinh;
        return $stmt->execute($data);
    }

    public function delete($maSV) {
        $sinhVien = $this->getById($maSV);
        if ($sinhVien && file_exists($this->uploadDir . basename($sinhVien['Hinh']))) {
            unlink($this->uploadDir . basename($sinhVien['Hinh']));
        }

        $query = "DELETE FROM SinhVien WHERE MaSV = :MaSV";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['MaSV' => $maSV]);
    }

    public function getById($maSV) {
        $query = "SELECT sv.*, nh.TenNganh FROM SinhVien sv JOIN NganhHoc nh ON sv.MaNganh = nh.MaNganh WHERE sv.MaSV = :MaSV";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['MaSV' => $maSV]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllNganhHoc() {
        $query = "SELECT * FROM NganhHoc";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    private function uploadImage($file) {
        if ($file['error'] == 0) {
            $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array($imageFileType, $allowedTypes)) {
                // Chuẩn hóa tên file: loại bỏ ký tự đặc biệt, thêm timestamp
                $fileName = pathinfo($file['name'], PATHINFO_FILENAME);
                $fileName = preg_replace('/[^a-zA-Z0-9]/', '_', $fileName); // Thay ký tự đặc biệt bằng dấu _
                $newFileName = $fileName . '_' . time() . '.' . $imageFileType; // Thêm timestamp để tránh trùng
                $targetFile = $this->uploadDir . $newFileName;

                if (move_uploaded_file($file['tmp_name'], $targetFile)) {
                    return $newFileName;
                }
            }
        }
        return false;
    }
}
?>