<?php
include_once('../../configuracion.php');
include_once('../../Templates/header.php');

if (!$MI_SESION->validar()) {
    Header('Location: ../index.php');
}

$id_compra = $_GET['orden'];

$total = 0;
$objCompra = new Compra();
$usuario = $MI_SESION->getUsuario();
$id_usuario = $usuario->getIdUsuario();
$roles_user_actual = $MI_SESION->getUsuarioRolesLogueado();

$listaCompraItem = $objCompra->traerProductosDeOrden($id_compra, $id_usuario);
/* echo '<pre>';
var_dump($listaCompraItem);
echo '</pre>'; */
$estado_compra = $objCompra->getUltimoEstadoCompra($id_compra);

if (empty($listaCompraItem) && !in_array(1, $roles_user_actual)) {
    Header('Location: ../../index.php');
    
}

?>

<h2 class="text-center">Orden N°:<?= $id_compra ?></h2>
<section>
    <div class="container mt-5 mb-5">
        <?php
        switch ($estado_compra) {
            case '2':
                $txt = '¡Se recibió el pago de tu compra! En breve se enviará el producto';
                $clase = 'estado-compra_realizada';
                break;
            case '3':
                $txt = '¡El Producto ha sido enviado correctamente!';
                $clase = 'estado-compra_enviada';
                break;
            case '4':
                $txt = '¡Compra Cancelada! Lamentamos informarte que tu compra se ha cancelado';
                $clase = 'estado-compra_cancelada';
                break;
        }
        ?>
        <h5 class="text-center mb-4 <?= $clase ?>"><?= $txt ?></h5>
        <table width="100%" style="text-align: center;">
            <thead>
                <th>Producto</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Detalle</th>
            </thead>

            <tbody>
                <?php foreach ($listaCompraItem['compra_items'] as $item) :
                    $total = $total + ($item->getCicantidad() * $item->getObjProducto()->getPrecio())
                ?>
                    <tr>
                        <td style="width: 200px; padding: 15px; height: 200px;"><img style="max-width: 150px;min-height: 150px;object-fit: cover;width: 100%;height: auto;border-radius: 10px;" src="../../uploads/fotosproductos/<?= $item->getObjProducto()->getUrlImagen() ?>"></td>
                        <td><?= $item->getObjProducto()->getPronombre() ?></td>
                        <td><?= $item->getObjProducto()->getPrecio() ?></td>
                        <td><?= $item->getCicantidad() ?></td>
                        <td><?= $item->getObjProducto()->getProdetalle() ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td><b>$<?= $total ?></b></td>
                </tr>
            </tbody>

            <tbody id="tableContainer"></tbody>
        </table>

        <div>
            <a class="btn btn-primary" href="miperfil.php" style="font-size: 14px;">Volver a mi perfil</a>
        </div>
    </div>
</section>

<?php include_once('../../Templates/footer.php') ?>