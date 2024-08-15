<?php 
include_once('../../configuracion.php');
$roles_user_actual = $MI_SESION->getUsuarioRolesLogueado();
$acceso = acceso($roles_user_actual, 2);
if (!$acceso) {
    Header('Location: ../../index.php');
}
include_once('../../Templates/header.php');

$objProducto = new Producto();
$productos = $objProducto->getProductos();
?>
<?php if ($MI_SESION->validar()) { ?>

<h3 class="p-4" style="text-align: center;">GESTIÓN DE PRODUCTOS</h3>
<section>
    <div class="container mt-5 mb-5">
        <table class="table table-hover"  width="100%" style="text-align: center;">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>PRODUCTO</th>
                    <th>NOMBRE</th>
                    <th>DETALLE</th>
                    <th>STOCK</th>
                    <th>PRECIO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody id="tableContainer"></tbody>
        </table>
    </div>
</section>
<?php } else {
    header('Location: ../../index.php');
}
?>
<!-- Modal de Edición Producto -->
<div class="modal fade" id="modal_edicion_producto" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" style="text-align: center;">EDITAR PRODUCTO</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formulario" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                   
                    <div class="row">
                        <div class="col-xs-12 col-md-12 mb-3">
                            <div class="form-group">
                                <label class="mb-2">Nombre</label>
                                <input type="text" class="form-control inpEdit" id="pronombre" name="pronombre">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 mb-3">
                            <div class="form-group">
                                <label class="mb-2">Detalle</label>
                                <input type="text" class="form-control inpEdit" id="prodetalle" name="prodetalle">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 mb-3">
                            <div class="form-group">
                                <label class="mb-2">Stock</label>
                                <input type="number" class="form-control inpEdit" id="procantstock" name="procantstock">
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-12 mb-3">
                            <div class="form-group">
                                <label class="mb-2">Precio</label>
                                <input type="number" class="form-control inpEdit" id="proprecio" name="proprecio">
                            </div>
                        </div>

                        <input type="hidden" id="idproducto" name="idproducto">
                        <div class="d-flex flex-column ">
                            <label class="mb-2">Imagen</label>
                            <input type="file" class="form-control" name="edit_img" id="edit_img">
                            <img id="img_add" class="img_add p-2" style="width: 150px; height: 200px;" src="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"  data-bs-dismiss="modal" style="border: 1px solid #000; border-radius: 0;background-color: #fff;color: #000;">Cerrar</button>
                    <input type="submit"  id="btnSubmit" value="Guardar Edición" class="btn btn-primary" style="border: 1px solid #000; border-radius: 0;background-color: #fff;color: #000;">
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../../Templates/footer.php'); ?>

<script>
let principal = <?= json_encode($PRINCIPAL); ?>;
</script>

<script src="../../Assets/js/producto/index.js"></script>
