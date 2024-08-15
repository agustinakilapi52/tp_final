<?php
include_once('../../configuracion.php');

$roles_usuarios = $MI_SESION->getUsuarioRolesLogueado();
$acceso = acceso($roles_usuarios, 2);
if (!$acceso) {
    Header('Location: ../../index.php');
}
include_once('../../Templates/header.php');
$objCompra = new Compra();
$listaCompras = $objCompra->getLastEstadoCompra();
?>

<style>
    #tableEstados th {
        background-color: #f2f2f2;
    }

    #tableEstados tr {
        border-bottom: 1px solid #ccc;
    }

    #tableEstados td {
        padding: 5px;
    }

    #tableEstados tr:hover {
        background-color: #f2f2f2;
    }
</style>

<h2 style="text-align: center;">Gestión de Compras </h2>
<section>
    <div class="container mt-5 mb-5">
        <table width="100%" style="text-align: center;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tableEstados">
                <?php foreach ($listaCompras as $lista): ?>
                    <tr>
                        <td><?= $lista['id_compra'] ?></td>
                        <td><?= $lista['cliente'] ?></td>
                        <?php if ($lista['estado'] == 1) { ?>
                            <td>Iniciado <small><em>(En espera)</em></small></td>
                        <?php } else if ($lista['estado'] == 2) { ?>
                            <td>Aceptado</td>
                        <?php } else if ($lista['estado'] == 3) { ?>
                            <td>Enviado</td>
                        <?php } else if ($lista['estado'] == 4) { ?>
                            <td>Cancelado</td>
                        <?php }  ?>
                        <td>
                            <?php if ($lista['estado'] != 1): ?>
                                <a href="ver_orden_cliente.php?orden=<?= $lista['id_compra'] ?>" class="btn btn-secondary btn-sm">Ver</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include_once('../../Templates/footer.php'); ?>

<script>
    let principal = <?= json_encode($PRINCIPAL); ?>;
</script>

<script src="../Assets/js/compras/index.js"></script>