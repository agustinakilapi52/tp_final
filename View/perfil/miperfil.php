<?php
include_once('../../configuracion.php');
include_once('../../Templates/header.php');

if (!$MI_SESION->validar()) {
    Header('Location: ../index.php');
} 
$usuario = $MI_SESION->getUsuario();
$objCompra = new Compra();
$ordenes = $objCompra->buscarOrdenesDeCompra($usuario->getIdUsuario());
if ($usuario->getImagenPerfil() != '') {
    $ruta = '../../uploads/fotosusuario/' . $usuario->getImagenPerfil();
} else {
    $ruta = '../../Assets/images/default-user.jpg';
}
?>
<link rel="stylesheet" href="../../Assets/css/perfil/perfil.css">
<h2 style="text-align: center;padding: 40px;" >Mi Perfil </h2>
<section>
    <div class="container mt-5 mb-5">
        <form class="needs-validation" id="form_actualizar" novalidate method="POST" enctype="multipart/form-data">
            <div class="d-flex gap-5">

                <div class="imagendiv">
                    <img id="img_add" src="<?= $ruta ?>" alt="">
                    <input type="file" name="edit_img" onChange="displayImage(this)" id="edit_img" hidden>
                    <button class="btn btn-sm" id="changeImg">Cambiar Imagen</button>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label">Nombre</label>
                            <input type="text" value="<?= $usuario->getUserNombre() ?>" name="nombre" id="nombre" class="form-control inpEdit" required>
                            <div class="invalid-feedback">
                                El campo 'nombre' no puede ir vacío
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label">Teléfono</label>
                            <input type="email" placeholder="Ingrese su teléfono" name="telefono" id="telefono" value="<?= $usuario->getUserTelefono() ?>" class="form-control inpEdit">
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label">E-Mail</label>
                            <input type="email" placeholder="Ingrese su correo" name="correo" id="correo" value="<?= $usuario->getUserMail() ?>" class="form-control inpEdit" required>
                            <div class="invalid-feedback">
                                El campo 'e-mail' no puede ir vacío
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-6 mb-3">
                        <div class="form-group">
                            <label class="form-label">Contraseña</label>
                            <input type="password" placeholder="Ingrese la nueva contraseña" name="password" id="password" class="form-control inpEdit">
                        </div>
                    </div>
                </div>

                <input type="hidden" name="idusuario" id="idusuario" value="<?= $usuario->getIdUsuario() ?>">
            </div>
            <div class="d-flex justify-content-end">
                <input type="submit" value="Actualizar datos" id="btnActualizarPerfil" class="btn btn-primary" style="font-size: 14px;">
            </div>
        </form>
    </div>
</section>

<style>
    .orden {
        border: 1px solid lightgray;
        padding: 7px;
        border-radius: 5px;
        transition: all .3s ease-in-out;
        cursor: pointer;
        margin-bottom: 5px;
    }

    .orden:hover {
        background-color: #f1f1f1;
    }
</style>

<section>
    <div class="container mt-5 mb-5">
        <div style="overflow-y: scroll; height: 400px;">
            <?php foreach ($ordenes as $orden) : ?>
                <h5 class="orden"><a href="orden.php?orden=<?= $orden['id_compra'] ?>" style="display: block; color: black;text-decoration: none;">ORDEN N°<?= $orden['id_compra'] ?></a></h5>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
    function displayImage(e) {
    if (e.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('img_add').setAttribute('src', e.target.result);
        }
        reader.readAsDataURL(e.files[0]);
    }
}
</script>
<script src="../../Assets/js/perfil/perfil.js"></script>

<?php include_once('../../Templates/footer.php'); ?>
