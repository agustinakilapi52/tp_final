<?php 

include_once('../../configuracion.php');
$roles_user_actual = $MI_SESION->getUsuarioRolesLogueado();
$acceso = acceso($roles_user_actual, 2);
if (!$acceso) {
    Header('Location: ../../index.php');
}
include_once('../../Templates/header.php');
$objUsuario = new Usuario();
$usuario = $objUsuario->getAllUsersHistorico();



?>
<?php if ($MI_SESION->validar()) { ?>
<h2 class="p-4" style="text-align: center;" >Gestión de Usuarios </h2>
<section>


    <div class="container mt-5 mb-5">
        <table class="table table-hover" width="100%" style="text-align: center;">
                <thead>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Telefono</th>
                    <th>Mail</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </thead>

                <tbody id="tableContainer"></tbody>
            </table>
        </div>
</section>
<?php } else {
    header('Location: ../../index.php');
}
?>

<div class="modal fade" id="modal_edicion_usuario" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">EDITAR USUARIO</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formulario" method="POST" class="needs-validation" novalidate enctype="multipart/form-data">
                    <div class="modal-body">
                      
                        <div class="form-group">
                            <label for="usnombre">Nombre Usuario</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                            <div class="invalid-feedback">
                            El campo  no puede ir vacío
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="ustelefono">Telefono</label>
                            <input type="number" class="form-control" id="telefono" name="telefono" required>
                            <div class="invalid-feedback">
                            El campo  no puede ir vacío
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="usmail">Mail</label>
                            <input type="text" type="email" class="form-control" id="correo" name="correo" required>
                            <div class="invalid-feedback">
                            El campo  no puede ir vacío
                            </div>
                        </div>
                        <input type="hidden" id="password" name="password">
                        <input type="hidden" id="idusuario" name="idusuario">

                        <div class="d-flex flex-column ">
                            <label class="mb-2">Imagen</label>
                            <input type="file" class="form-control" name="edit_img" id="edit_img">
                            <img id="img_add" class="img_add p-2" style="width: 150px; height: 200px;" src="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border: 1px solid #000; border-radius: 0;background-color: #fff;color: #000;">Cerrar</button>
                        <button type="submit" id="btnSubmit" class="btn btn-primary" style="border: 1px solid #000; border-radius: 0;background-color: #fff;color: #000;">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



    <?php include_once('../../Templates/footer.php') ?>

<script>
let principal = <?= json_encode($PRINCIPAL); ?>
</script>

<script src="../../Assets/js/usuario/index.js"></script>