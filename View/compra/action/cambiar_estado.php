<?php

include_once('../../../configuracion.php');

$datos = $_POST;
$objCompra = new Compra();
$results = $objCompra->changeStateCompra($datos['id_compra'], $datos['tipo']);
echo json_encode($results);

?>