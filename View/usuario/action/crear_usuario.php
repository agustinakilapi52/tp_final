<?php
include('../../../configuracion.php');

$datos = $_POST;
$datos['edit_img'] = $_FILES["edit_img"]; 
$objUser = new Usuario();
$respuesta = $objUser->crearUsuario($datos);



echo $respuesta;
