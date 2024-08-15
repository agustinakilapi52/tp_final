<?php

include('../../../configuracion.php');

$usuario_edit = new Usuario();
$datos = $_POST;
$datos['edit_img'] = $_FILES["edit_img"]; 
$respuesta = $usuario_edit->actualizarDatosPerfil($datos);
