<?php 
include_once('../../../configuracion.php');

$objUsuario = new Usuario();
$listaUsuario = $objUsuario->getAllUsersHistorico();

header('Content-Type: application/json');
echo json_encode($listaUsuario);
?>