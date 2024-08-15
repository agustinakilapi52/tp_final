<?php 
include_once('../../../configuracion.php');

$objProducto = new Producto();
$listaProductos = $objProducto->getProductosHistorico();

header('Content-Type: application/json');
echo json_encode($listaProductos);

?>