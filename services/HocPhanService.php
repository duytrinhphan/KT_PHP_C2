<?php
require_once '../config/database.php';

class HocPhanService {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAll() {
        $query = "SELECT * FROM HocPhan";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($maHP) {
        $query = "SELECT * FROM HocPhan WHERE MaHP = :MaHP";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['MaHP' => $maHP]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function decreaseQuantity($maHP) {
        // Lấy thông tin học phần để biết SoTinChi
        $hp = $this->getById($maHP);
        if ($hp && $hp['SoLuongDuKien'] >= $hp['SoTinChi']) {
            $query = "UPDATE HocPhan SET SoLuongDuKien = SoLuongDuKien - :SoTinChi WHERE MaHP = :MaHP";
            $stmt = $this->db->prepare($query);
            return $stmt->execute(['MaHP' => $maHP, 'SoTinChi' => $hp['SoTinChi']]);
        }
        return false; // Không giảm nếu số lượng không đủ
    }
}
?>