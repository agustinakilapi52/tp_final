<?php
include_once('../../configuracion.php');
$roles_user_actual = $MI_SESION->getUsuarioRolesLogueado();
$acceso = acceso($roles_user_actual, 2);
if (!$acceso) {
    Header('Location: ../../index.php');
}

include_once('../../Templates/header.php');
?>
<?php if ($MI_SESION->validar()) { ?>
<div class="container">

    <h2 class="p-4" style="text-align: center;" >Crear Producto</h2>
    <section>
    <div class="container mt-5 mb-5">
        <form id="formulario" class="needs-validation" novalidate method="POST" enctype="multipart/form-data">
           
            <div class="col-10 mb-3">
                <div class="form-group">
                    <label class="form-label" style="font-size: 15px; color: #808080; ">Nombre Producto</label>
                    <input type="text" name="pronombre" id="pronombre" class="form-control inpEdit"  required>
                    <div class="invalid-feedback">
                        El campo 'nombre producto' no puede ir vacío
                    </div>
                </div>
            </div>

            <div class="col-10 mb-3">
                <div class="form-group">
                    <label class="form-label" style="font-size: 15px; color: #808080; ">Detalle</label>
                    <input type="text" name="prodetalle" id="prodetalle" class="form-control inpEdit" required>
                    <div class="invalid-feedback">
                        El campo 'detalle' no puede ir vacío
                    </div>
                </div>
            </div>

            <div class="col-10 mb-3">
                <div class="form-group">
                    <label class="form-label" style="font-size: 15px; color: #808080; ">Stock</label>
                    <input type="number" name="procantstock" id="procantstock" class="form-control inpEdit" required>
                    <div class="invalid-feedback">
                        El campo 'stock' no puede ir vacío
                    </div>
                </div>
            </div>

            <div class="col-10 mb-3">
                <div class="form-group">
                    <label class="form-label" style="font-size: 15px; color: #808080; ">Precio </label>
                    <input type="text" name="precio" id="precio" class="form-control inpEdit" required>
                    <div class="invalid-feedback">
                        El campo 'precio' no puede ir vacío
                    </div>
                </div>
            </div>

            <div class="col-10 mb-3">
                <img id="img_add" src="<?= $ruta ?>" alt="">
                <input type="file" class="form-control form-control" name="edit_img" onChange="displayImage(this)" id="edit_img">
         
            </div>
           

            <div class="justify-content-end mt-4">
                <input type="submit" value="Crear Producto" id="btnSubmit" class="btn btn-primary" class="btn btn-primary btn-block" style="border: 1px solid #000; border-radius: 0;background-color: #fff;color: #000;">
            </div>
        </form>
    </div>

</section>


</div>

<?php include_once('../../Templates/footer.php') ?>
<?php } else {
    header('Location: ../../index.php');
}
?>
<script>
    let principal = <?= json_encode($PRINCIPAL); ?>;
</script>
<script src="../../Assets/js/producto/add.js"></script>
