<?php
include('../../../configuracion.php');

$usuario = $MI_SESION->getUsuario();
$objCompra = new Compra();
$listaItemsCarrito = [];
if ($usuario) {
    $listaItemsCarrito = $objCompra->getItemsCarrito($usuario->getIdUsuario());
}
echo json_encode($listaItemsCarrito);

?>