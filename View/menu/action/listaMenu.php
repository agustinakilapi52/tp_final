<?php 
include_once('../../../configuracion.php');

$objMenu = new Menu();
$listaMenu = $objMenu->getMenusHistorico();

header('Content-Type: application/json');
echo json_encode($listaMenu);
?>