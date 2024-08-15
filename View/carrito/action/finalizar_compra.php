<?php
include_once('../../../configuracion.php');

$id_compra = $_POST['id_compra'];
$objCompra = new Compra();
$result = $objCompra->finalizarCompra($id_compra);
echo json_encode($result);