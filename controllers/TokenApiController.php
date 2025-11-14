<?php
require_once __DIR__ . '/../models/TokenApi.php';
require_once __DIR__ . '/../models/ClientApi.php';

class TokenApiController
{
    private $tokenApiModel;
    private $clientApiModel;

    public function __construct()
    {
        $this->tokenApiModel = new TokenApi();
        $this->clientApiModel = new ClientApi();
    }

    public function obtenerTokens()
    {
        return $this->tokenApiModel->obtenerTokens();
    }

    public function obtenerToken($id)
    {
        return $this->tokenApiModel->obtenerTokenPorId($id);
    }

    public function obtenerTokensPorCliente($id_client_api)
    {
        return $this->tokenApiModel->obtenerTokensPorCliente($id_client_api);
    }

    public function crearToken($id_client_api)
    {
        $token = $this->tokenApiModel->generarToken($id_client_api);
        return $this->tokenApiModel->guardarToken($id_client_api, $token);
    }

    public function editarToken($id, $estado)
    {
        return $this->tokenApiModel->actualizarToken($id, $estado);
    }

    public function borrarToken($id)
    {
        return $this->tokenApiModel->eliminarToken($id);
    }

    public function obtenerClientes()
    {
        return $this->clientApiModel->obtenerClientes();
    }

    public function obtenerTokenPorToken($token)
    {
        $stmt = $this->tokenApiModel->getConexion()->prepare("
            SELECT t.*, c.estado AS cliente_estado
            FROM tokens_api t
            JOIN client_api c ON t.id_client_api = c.id
            WHERE t.token = ? AND t.estado = 1 AND c.estado = 1
        ");

        $stmt->bind_param("s", $token);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
}
?>
