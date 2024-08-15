<?php

include('../../../configuracion.php');

$userEdit = new Usuario();
$datos = $_POST;
$datos['edit_img'] = $_FILES["edit_img"]; 

$userEdit->actualizarDatosPerfil($datos);
$login = $MI_SESION->actualizarDataUsuario($datos);

?>
