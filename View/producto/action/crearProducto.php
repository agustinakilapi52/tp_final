<?php
include('../../../configuracion.php');
$datos = $_POST;
$datos['edit_img'] = $_FILES["edit_img"]; 
$objProducto = new Producto();
$respuesta = $objProducto->crearProducto($datos);
echo $respuesta;
?>