<?php
require_once __DIR__ . '/../config/database.php';

class TokenApi
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = conectarDB();
    }

    public function getConexion()
    {
        return $this->conexion;
    }

    public function obtenerTokens()
    {
        $query = "SELECT t.*, c.razon_social FROM tokens_api t JOIN client_api c ON t.id_client_api = c.id";
        $resultado = $this->conexion->query($query);
        $data = [];
        while ($fila = $resultado->fetch_assoc()) {
            $data[] = $fila;
        }
        return $data;
    }

    public function obtenerTokenPorId($id)
    {
        $stmt = $this->conexion->prepare("SELECT t.*, c.razon_social FROM tokens_api t JOIN client_api c ON t.id_client_api = c.id WHERE t.id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function obtenerTokensPorCliente($id_client_api)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM tokens_api WHERE id_client_api = ?");
        $stmt->bind_param("i", $id_client_api);
        $stmt->execute();
        $result = $stmt->get_result();
        $tokens = [];

        while ($row = $result->fetch_assoc()) {
            $tokens[] = $row;
        }

        return $tokens;
    }

    public function guardarToken($id_client_api, $token)
    {
        $stmt = $this->conexion->prepare("INSERT INTO tokens_api (id_client_api, token) VALUES (?, ?)");
        $stmt->bind_param("is", $id_client_api, $token);
        return $stmt->execute() ? $stmt->insert_id : false;
    }

    public function actualizarToken($id, $estado)
    {
        $stmt = $this->conexion->prepare("UPDATE tokens_api SET estado = ? WHERE id = ?");
        $stmt->bind_param("ii", $estado, $id);
        return $stmt->execute();
    }

    public function eliminarToken($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM tokens_api WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function generarToken($id_client_api)
    {
        $caracteresAleatorios = bin2hex(random_bytes(16));
        $fechaRegistro = date('Ymd'); 
        $token = $caracteresAleatorios . '-' . $fechaRegistro . '-' . str_pad($id_client_api, 2, '0', STR_PAD_LEFT);
        return $token;
    }
}
?>
