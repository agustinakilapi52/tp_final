<?php
include_once('../../../configuracion.php');
header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$objMenu = new Menu();

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['idmenu'])) {
    $id_menu = $data['idmenu'];
    $estado = $data['estado'];

    $resultado = $objUsuario->estadoUsuario(['idmenu' => $id_menu, 'estado' => $estado]);

    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del usuario']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado']);
}
?>
