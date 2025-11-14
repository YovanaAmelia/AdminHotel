<?php

class HotelController {

    private $db;

    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = new Database();
    }

    public function buscarHoteles($search) {
        $conn = $this->db->connect();

        $sql = "SELECT * FROM hoteles
                WHERE nombre LIKE ? 
                OR direccion LIKE ?
                OR tipos_habitacion LIKE ?";

        $stmt = $conn->prepare($sql);
        $param = "%$search%";
        $stmt->bind_param("sss", $param, $param, $param);

        $stmt->execute();
        $result = $stmt->get_result();

        $hoteles = [];
        while ($row = $result->fetch_assoc()) {
            $hoteles[] = $row;
        }

        return $hoteles;
    }
}
?>
