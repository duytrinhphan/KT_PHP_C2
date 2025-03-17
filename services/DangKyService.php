<?php
require_once '../config/database.php';

class DangKyService {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function saveDangKy($maSV, $hocPhans) {
        $this->db->beginTransaction();
        try {
            $query = "INSERT INTO DangKy (NgayDK, MaSV) VALUES (CURDATE(), :MaSV)";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['MaSV' => $maSV]);
            $maDK = $this->db->lastInsertId();

            $hocPhanService = new HocPhanService();
            foreach ($hocPhans as $maHP) {
                if (!$hocPhanService->decreaseQuantity($maHP)) {
                    throw new Exception("Số lượng dự kiến không đủ cho học phần $maHP");
                }
                $query = "INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (:MaDK, :MaHP)";
                $stmt = $this->db->prepare($query);
                $stmt->execute(['MaDK' => $maDK, 'MaHP' => $maHP]);
            }
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getDangKyBySinhVien($maSV) {
        $query = "SELECT dk.MaDK, hp.MaHP, hp.TenHP, hp.SoTinChi 
                 FROM DangKy dk 
                 JOIN ChiTietDangKy ctdk ON dk.MaDK = ctdk.MaDK 
                 JOIN HocPhan hp ON ctdk.MaHP = hp.MaHP 
                 WHERE dk.MaSV = :MaSV";
        $stmt = $this->db->prepare($query);
        $stmt->execute(['MaSV' => $maSV]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteHocPhan($maDK, $maHP) {
        $query = "DELETE FROM ChiTietDangKy WHERE MaDK = :MaDK AND MaHP = :MaHP";
        $stmt = $this->db->prepare($query);
        return $stmt->execute(['MaDK' => $maDK, 'MaHP' => $maHP]);
    }

    public function deleteDangKy($maDK) {
        $this->db->beginTransaction();
        try {
            $query = "DELETE FROM ChiTietDangKy WHERE MaDK = :MaDK";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['MaDK' => $maDK]);

            $query = "DELETE FROM DangKy WHERE MaDK = :MaDK";
            $stmt = $this->db->prepare($query);
            $stmt->execute(['MaDK' => $maDK]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
}
?>