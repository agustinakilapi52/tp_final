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
$objMenu = new Menu();
$menu = $objMenu->getAllMenus();

?>

<?php if ($MI_SESION->validar()) { ?>
<h2 style="text-align: center;">GESTIÓN MENU</h2>
<section>
    <div class="container mt-5 mb-5">
        <table class="table table-hover" width="100%" style="text-align: center;">
            <thead>
                <th>ID</th>
                <th>NOMBRE</th>
                <th>RUTA</th>
                <th>MENU PADRE</th>
                <th>ESTADO</th>
                <th>ACCIONES</th>
            </thead>
            <tbody id="tableContainer"></tbody>
        </table>
    </div>
</section>

<div class="modal fade" id="modal_edicion_menu" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">EDITAR MENÚ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formulario" method="POST">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="menombre">Nombre del Menú</label>
                        <input type="text" class="form-control" id="menombre" name="menombre">
                    </div>
                    <div class="form-group">
                        <label for="medescripcion">Descripción</label>
                        <input type="text" class="form-control" id="medescripcion" name="medescripcion">
                    </div>
                    <div class="form-group">
                        <label for="idpadre">Menú Padre</label>
                        <select name="idpadre" id="idpadre" class="form-control inpEdit">
                            <option value="">-- Seleccione --</option>
                            <?php for ($i = 0; $i < count($menu); $i++) : ?>
                                <option value="<?= $menu[$i]->getIdMenu() ?>"><?= $menu[$i]->getMeNombre() ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="" class="form-label">Roles</label>
                    </div>
                    <div class="row" style="margin-left: 1px;">
                        <?php for ($i = 0; $i < count($roles); $i++) { ?>
                            <div class="col-md-4 divGrupo">
                                <input type="checkbox" class="checked_grupo" value="<?= $roles[$i]->getIdRol() ?>" name="roles[]" id="<?= $roles[$i]->getIdRol() ?>" hidden>
                                <label for="<?= $roles[$i]->getIdRol() ?>"><?= $roles[$i]->getRoDescripcion() ?> </label>
                                <input type="checkbox" class="no_checked_grupo" name="no_roles[]" value="<?= $roles[$i]->getIdRol() ?>" >
                            </div>
                        <?php } ?>
                    </div>
                    <input type="hidden" id="idmenu" name="idmenu">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" id="btnSubmitMenu" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
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

<script src="../../Assets/js/menu/index.js"></script>
