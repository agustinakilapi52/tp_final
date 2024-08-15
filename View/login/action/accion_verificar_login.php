<?php

include('../../../configuracion.php');

// Imprime los datos recibidos para depuración
// error_log(print_r($_POST, true));

$datos = data_submitted($_POST);

if (isset($datos['usnombre']) && isset($datos['uspass'])) {
    $login = $MI_SESION->iniciar($datos['usnombre'], $datos['uspass']);
    if ($login) {
        $resultado['exito'] = true;
        echo json_encode($resultado);
    } else {
        $resultado['errors'][] = '¡Ha surgido un error, vuelva a intentarlo!';
        http_response_code(400);
        echo json_encode($resultado['errors']);
    }
} else {
    // Si 'usnombre' o 'uspass' no están presentes en $_POST
    $resultado['errors'][] = 'Datos de login incompletos.';
    http_response_code(400);
    echo json_encode($resultado['errors']);
}
