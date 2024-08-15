<?php

include('../../../configuracion.php');

$producto_edit = new Producto();
$datos = $_POST;
$datos['edit_img'] = $_FILES["edit_img"]; 
$respuesta = $producto_edit->actualizarProducto($datos);
