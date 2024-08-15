<?php
include_once('../../configuracion.php');
include_once('../../Templates/header.php');
$id_compra = $_GET['orden'];
$objCompra = new Compra();
$listaCompraItem = $objCompra->traerProductosDeOrden($id_compra);
?>
<h2 style="text-align: center;">Orden: #<?= $id_compra ?></h2>

<section>
    <div class="text-center mt-4" style="width: 50%; margin: 0 auto;">
        <h5>Estados de la Compra</h5>
        <hr>
    </div>
    <?php foreach ($listaCompraItem['all_estados'] as $state) :
    ?>
        <div class="container mb-2" style="width: 60%;border-left: 5px solid #e6d6ad;border-top: 1px solid lightgray;border-right: 1px solid lightgray;border-bottom: 1px solid lightgray;border-radius: 0 3px 3px 0;">
            <div data-bs-toggle="collapse" data-bs-target="#<?= $state->getObjcompraestadotipo()->getCetdescripcion() ?>" aria-expanded="false" aria-controls="<?= $state->getObjcompraestadotipo()->getCetdescripcion() ?>" style="padding: 10px; color: black; margin: 0; cursor: pointer;">
                <p class="p-0 m-0">Estado <?= $state->getObjcompraestadotipo()->getCetdescripcion() ?>
                &#8595;
            </p>
                
            </div>
            <div class="collapse mb-2" id="<?= $state->getObjcompraestadotipo()->getCetdescripcion() ?>">
                <div class="card card-body">
                    <b> Compra <?= $state->getObjcompraestadotipo()->getCetdescripcion() ?> el día:</b> <?= $state->getCefechaini() ?> <b>Estado de la misma:</b> Finalizada el día <?= $state->getCefechafin() ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</section>

<section>
    <div class="container mt-5 mb-5">

        <!-- Si alcanzas, dejalo, sino, borralo -->
        <!-- <p>Estado: <em> <b>Enviado</b></em></p>
        <p>Fecha de la compra: <em> <b>23/02/23</b></em></p> -->

        <table width="100%" style="text-align: center;">
            <thead>
                <th>Producto</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Detalle</th>
            </thead>

            <tbody>
                <?php foreach ($listaCompraItem['compra_items'] as $item) : ?>
                    <tr>
                        <td style="width: 200px; padding: 15px; height: 200px;"><img style="max-width: 150px;min-height: 150px;object-fit: cover;width: 100%;height: auto;border-radius: 10px;" src="../../uploads/fotosproductos/<?= $item->getObjProducto()->getUrlImagen() ?>"></td>
                        <td><?= $item->getObjProducto()->getPronombre() ?></td>
                        <td><?= $item->getObjProducto()->getPrecio() ?></td>
                        <td><?= $item->getObjProducto()->getProdetalle() ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>

        <div class="row">
            <div class="col-xs-12 col-md-3">
                <a class="btn btn-primary" href="compras.php" style="font-size: 14px;">Volver a compras</a>
            </div>
            <div class="col-xs-12 col-md-5"></div>

            <?php if ($listaCompraItem['estado_compra'] <= 2) { ?>
                <div class="col-xs-12 col-md-4 d-flex">
                    <button style="font-size: 14px; margin-right: 5px;" class="btn btn-primary" id="btnCancelarPedido" data-id="0">Cancelar Pedido</button>
                    <button style="font-size: 14px;" class="btn btn-secondary" id="btnEnviarPedido" data-id="1">Enviar Pedido</button>
                </div>
            <?php }  ?>

        </div>
    </div>
</section>
<script>
    let id_compra = <?= json_encode($id_compra); ?>;
    let principal = <?= json_encode($PRINCIPAL); ?>;
</script>
<?php include_once('../../Templates/footer.php'); ?>
<script src="../../Assets/js/compras/orden.js"></script>
