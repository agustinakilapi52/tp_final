<?php

include('../../../configuracion.php');

$menu_edit = new Menu();
$datos = $_POST;
$respuesta = $menu_edit-> actualizarDatosMenu($datos);
?> 