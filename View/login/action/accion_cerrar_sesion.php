<?php
include_once('../../../configuracion.php');

if ($MI_SESION->cerrar()) {
    header('Location: ../../../index.php');
}

?>