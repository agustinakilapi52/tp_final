<?php 
include('../../../configuracion.php');

/* Acá voy agregar un producto al carrito e iniciar la compra */

$objCompra = new Compra();
$usuario = $MI_SESION->getUsuario();
$datos = $_POST;
$mensaje = [
    'exito' => true,
    'error' => ''
];

// Si el usuario está logueado entonces se puede agregar al carrito
if ($usuario) {
    $datos['idusuario'] = $usuario->getIdUsuario();
    $compraRealizada = $objCompra->iniciarCompra($datos);
    if (!$compraRealizada['exito']) {
        $mensaje = [
            'exito' => false,
            'error' => 'No hay mas stock disponible'
        ];
    }
} else {
    $mensaje = [
        'exito' => false,
        'error' => 'No se ha iniciado sesión'
    ];
}

echo json_encode($mensaje);

?>