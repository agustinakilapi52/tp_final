<?php
include_once('../../configuracion.php');
$roles_user_actual = $MI_SESION->getUsuarioRolesLogueado();
$acceso = acceso($roles_user_actual, 2);
if (!$acceso) {
    Header('Location: ../../index.php');
}
include_once('../../Templates/header.php');
$objRol = new Rol();
$roles = $objRol->getRoles();
?>
<?php if ($MI_SESION->validar()) { ?>
<div class="container">

    <h2 style="text-align: center;" >Crear Usuario</h2>
    <section>
    <div class="container mt-5 mb-5">
    <form id="formulario" class="needs-validation" novalidate method="POST" enctype="multipart/form-data">
    <div class="col-10 mb-3">
        <div class="form-group">
            <label class="form-label" style="font-size: 15px; color: #808080;">Nombre usuario</label>
            <input type="text" name="usnombre" id="usnombre" class="form-control inpEdit" required>
            <div class="invalid-feedback">
                El campo no puede ir vacío
            </div>
        </div>
    </div>
    <div class="col-10 mb-3">
        <div class="form-group">
            <label class="form-label" style="font-size: 15px; color: #808080;">Gmail</label>
            <input type="email" name="usmail" id="usmail" class="form-control inpEdit" required>
            <div class="invalid-feedback">
                El campo no puede ir vacío
            </div>
        </div>
    </div>
    <div class="col-10 mb-3">
        <div class="form-group">
            <label class="form-label" style="font-size: 15px; color: #808080;">Teléfono</label>
            <input type="tel" name="ustelefono" id="ustelefono" class="form-control inpEdit" required>
            <div class="invalid-feedback">
                El campo no puede ir vacío
            </div>
        </div>
    </div>
    <div class="col-10 mb-3">
        <div class="form-group">
            <label class="form-label" style="font-size: 15px; color: #808080;">Contraseña</label>
            <input type="password" name="uspass" id="uspass" class="form-control inpEdit" required>
            <div class="invalid-feedback">
                El campo no puede ir vacío
            </div>
        </div>
    </div>
   
    <div class="col-10 mb-3">
        <img id="img_add" src="<?= $ruta ?>" alt="">
        <input type="file" class="form-control form-control" name="edit_img" onChange="displayImage(this)" id="edit_img">
         
    </div>
 
    <div class="col-10 mb-3">
        <div class="form-group">
            <label for="" class="form-label"  style="font-size: 15px; color: #808080;">Asignar Roles</label>
        </div>
        <div class="row" style="margin-left: 1px;">
            <?php
            for ($i = 0; $i < count($roles); $i++) { ?>
                <div class="col-md-4 divGrupo">
                    <input type="checkbox" class="checked_grupo" value="<?= $roles[$i]->getIdRol() ?>" name="roles[]" id="<?= $roles[$i]->getIdRol() ?>">
                    <label for="<?= $roles[$i]->getIdRol() ?>"><?= $roles[$i]->getRoDescripcion() ?> </label>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="justify-content-end">
        <input type="submit" value="Registrarse" id="btnSubmit" class="btn btn-primary btn-block" style="border: 1px solid #000; border-radius: 0; background-color: #fff; color: #000;">
    </div>
</form>
    </div>

</section>


</div>
<?php } else {
    header('Location: ../../index.php');
}
?>
<?php include_once('../../Templates/footer.php') ?>
<script>
    let principal = <?= json_encode($PRINCIPAL); ?>;
</script>
<script src="../../Assets/js/usuario/add.js"></script>
