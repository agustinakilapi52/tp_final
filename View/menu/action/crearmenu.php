<?php
include('../../../configuracion.php');
$datos = $_POST;
$objMenu = new Menu();
$respuesta = $objMenu->crearMenu($datos);
echo $respuesta;
?>