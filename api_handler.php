<?php
// api_handler.php (HOTELESAPI)
header('Content-Type: application/json');

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/TokenApiController.php';
require_once __DIR__ . '/controllers/HotelController.php';

// Aceptar action tanto por GET como por POST
$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$token  = $_POST['token'] ?? '';

// -----------------------------
// 1. VALIDACIÓN SOLO PARA validarToken
// -----------------------------
if ($action === 'validarToken') {

    $tokenController = new TokenApiController();
    $tokenData = $tokenController->obtenerTokenPorToken($token);

    if (!$tokenData) {
        echo json_encode([
            'status' => false,
            'type'   => 'error',
            'msg'    => 'Token no encontrado en HOTELESAPI.'
        ]);
        exit();
    }

    if ($tokenData['estado'] != 1) {
        echo json_encode([
            'status' => false,
            'type'   => 'warning',
            'msg'    => 'Token inactivo en HOTELESAPI.'
        ]);
        exit();
    }

    echo json_encode([
        'status' => true,
        'type'   => 'success',
        'msg'    => 'Token válido en HOTELESAPI.'
    ]);
    exit();
}

// -----------------------------
// 2. VALIDAR TOKEN PARA TODAS LAS DEMÁS ACCIONES
// -----------------------------
$tokenController = new TokenApiController();
$tokenData = $tokenController->obtenerTokenPorToken($token);

if (!$tokenData) {
    echo json_encode([
        'status' => false,
        'type'   => 'error',
        'msg'    => 'Token no encontrado en HOTELESAPI.'
    ]);
    exit();
}

if ($tokenData['estado'] != 1) {
    echo json_encode([
        'status' => false,
        'type'   => 'warning',
        'msg'    => 'Token inactivo en HOTELESAPI.'
    ]);
    exit();
}

// -----------------------------
// 3. PROCESAR ACCIONES
// -----------------------------
$hotelController = new HotelController();

switch ($action) {

    case 'buscarHoteles':
        $search = $_POST['search'] ?? '';

        $hoteles = $hotelController->buscarHoteles($search);

        echo json_encode([
            'status' => true,
            'type'   => 'success',
            'data'   => $hoteles
        ]);
        break;

    default:
        echo json_encode([
            'status' => false,
            'type'   => 'error',
            'msg'    => 'Acción no válida.'
        ]);
}
?>
