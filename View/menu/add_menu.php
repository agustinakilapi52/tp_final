<?php
include_once('../../configuracion.php');
$roles_user_actual = $MI_SESION->getUsuarioRolesLogueado();
$acceso = acceso($roles_user_actual, 2);
if (!$acceso) {
    Header('Location: ../../index.php');
}

include_once('../../Templates/header.php');


$objRol = new Rol();
$objMenu = new Menu();
$roles = $objRol->getRoles();

$menues = $objMenu->getAllMenus();
?>

<?php if ($MI_SESION->validar()) { ?>
<div class="container">

    <h2 style="text-align: center;" >Crear Menu</h2>
   
 
    <div class="container mt-5 mb-5">
        <form id="formulario_menu" class="needs-validation" novalidate method="POST" enctype="multipart/form-data">

            <div style="width: 100%;">

                <div class="col-10 mb-3">
                    <div class="form-group">
                        <label class="form-label">Nombre Menú</label>
                        <input type="text" name="menombre" id="menombre" class="form-control inpEdit"  required>
                        <div class="invalid-feedback">
                            El campo 'nombre menú' no puede ir vacío
                        </div>
                    </div>
                </div>

                <div class="col-10 mb-3">
                    <div class="form-group">
                        <label class="form-label">Ruta</label>
                        <input type="text" name="medescripcion" id="medescripcion" class="form-control inpEdit" required>
                        <div class="invalid-feedback">
                            El campo 'ruta' no puede ir vacío
                        </div>
                    </div>
                </div>

                <div class="col-10 mb-3">
                    <div class="form-group">
                        <label class="form-label mt-3 mb-3">SubMenú</label>
                        <input type="checkbox" id="submenu">
                    </div>
                </div>

                <div class="col-10 mb-3">
                    <div class="form-group" id="containerIdPadre" style="display: none;">
                        <label class="form-label">Seleccione el menú padre</label>
                        <select name="idpadre" id="idpadre" class="form-control inpEdit">
                            <option value="">-- Seleccione --</option>
                            <?php for ($i = 0; $i < count($menues); $i++) : ?>
                                <option value="<?= $menues[$i]->getIdMenu() ?>"><?= $menues[$i]->getMeNombre() ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="col-10 mb-3">
                    <div class="form-group">
                        <label for="" class="form-label">Habilitar roles</label>
                    </div>
                    <div class="row" style="margin-left: 1px;">
                        <?php

                        for ($i = 0; $i < count($roles); $i++) { ?>

                            <div class="col-md-4 divGrupo">
                                <input type="checkbox" class="checked_grupo" value="<?= $roles[$i]->getIdRol() ?>" name="roles[]" id="<?= $roles[$i]->getIdRol() ?>" >
                                <label for="<?= $roles[$i]->getIdRol() ?>"><?= $roles[$i]->getRoDescripcion() ?> <i class="fa-solid fa-circle-check check_grupo" style="visibility: hidden;"></i></label>
                            </div>

                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="justify-content-end">
                <input type="submit" value="Crear Menu" id="btnSubmit" class="btn btn-primary" style="border: 1px solid #000; border-radius: 0;background-color: #fff;color: #000;">
            </div>
        </form>
    </div>

    
   


</div>

<?php include_once('../../Templates/footer.php') ?>
<?php } else {
    header('Location: ../../index.php');
}
?>
<script>
    let principal = <?= json_encode($PRINCIPAL); ?>;
</script>
<script src="../../Assets/js/menu/add.js"></script>
<script src="../../Assets/js/bootstrap.bundle.min.js"></script>