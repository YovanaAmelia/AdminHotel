<?php
error_reporting(0);
ini_set('display_errors', 0);
header('Content-Type: application/json; charset=utf-8');
ob_clean();

header('Content-Type: application/json');

require_once __DIR__ . '/controllers/HotelController.php';
require_once __DIR__ . '/controllers/TokenController.php';

// Aceptar acción por GET o POST
$action = $_GET['action'] ?? $_POST['action'] ?? '';

if (!$action) {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'No se recibió ninguna acción.'
    ]);
    exit();
}

// VALIDACIÓN DE TOKEN
$token = $_POST['token'] ?? $_GET['token'] ?? '';

$tokenController = new TokenController();

if (!$tokenController->esTokenValido($token)) {
    echo json_encode([
        'status' => false,
        'type' => 'error',
        'msg' => 'Token inválido o expirado.'
    ]);
    exit();
}

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
