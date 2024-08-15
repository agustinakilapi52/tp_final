<?php
include('../../../configuracion.php');
$objCompra = new Compra();
$usuario = $MI_SESION->getUsuario();
$datos = $_POST;
$datos['id_usuario'] = $usuario->getIdUsuario();
$compra_item = $objCompra->modificarCantProductosCarrito($datos);
?>