<?php
// api_handler.php (HOTELESAPI)
header('Content-Type: application/json');
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/TokenApiController.php';
require_once __DIR__ . '/controllers/HotelController.php';

// Obtener el token y la acción
$token = $_POST['token'] ?? '';
$action = $_GET['action'] ?? '';

// Validar el token en HOTELESAPI
if ($action === 'validarToken') {
    $tokenController = new TokenApiController();
    $tokenData = $tokenController->obtenerTokenPorToken($token);

    if (!$tokenData) {
        echo json_encode([
            'status' => false,
            'type' => 'error',
            'msg' => 'Token no encontrado en HOTELESAPI.'
        ]);
        exit();
    }

    if ($tokenData['estado'] != 1) {
        echo json_encode([
            'status' => false,
            'type' => 'warning',
            'msg' => 'Token inactivo en HOTELESAPI.'
        ]);
        exit();
    }

    echo json_encode([
        'status' => true,
        'type' => 'success',
        'msg' => 'Token válido en HOTELESAPI.'
    ]);
    exit();
}

// Procesar otras acciones (buscarHoteles, etc.)
$tokenController = new TokenApiController();
$tokenData = $tokenController->obtenerTokenPorToken($token);

if (!$tokenData) {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'Token no encontrado en HOTELESAPI.'
    ]);
    exit();
}

if ($tokenData['estado'] != 1) {
    echo json_encode([
        'status' => false,
        'type' => 'warning',
        'msg' => 'Token inactivo en HOTELESAPI.'
    ]);
    exit();
}

// Procesar la acción (ej: buscarHoteles)
$hotelController = new HotelController();
switch ($action) {
    case 'buscarHoteles':
        $search = $_POST['search'] ?? '';
        $hoteles = $hotelController->buscarHoteles($search);
        echo json_encode([
            'status' => true,
            'type' => 'success',
            'data' => $hoteles
        ]);
        break;
    default:
        echo json_encode([
            'status' => false,
            'type' => 'error',
            'msg' => 'Acción no válida.'
        ]);
}
?>
