<?php
include_once('../../../configuracion.php');
header('Content-Type: application/json'); // Asegura que la respuesta sea JSON

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$objUsuario = new Usuario();

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_usuario'])) {
    $id_usuario = $data['id_usuario'];
    $estado = $data['estado'];

    $resultado = $objUsuario->estadoUsuario(['id_usuario' => $id_usuario, 'estado' => $estado]);

    if ($resultado) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado del usuario']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ID de usuario no proporcionado']);
}
?>
