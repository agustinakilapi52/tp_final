<?php
include_once('../../configuracion.php');
include_once('../../Templates/header.php');

?>
<div class="container mt-5 mb-5" style="width: 50%; padding: 40px;">
          <form method="POST" id="form_login" class="needs-validation" novalidate>

              <div style="border: 1px solid lightgray; padding: 15px; border-radius: 10px;">
              <h2 style="color: black; font-size: 30px;position: relative; text-align: center;">Inicia Sesión</h2>
                  <div class="form-group mb-3">
                      <label for="usuario" class="mb-3">Usuario</label>
                      <input type="text" name="usnombre" id="usnombre" class="form-control inpEdit" placeholder="Ingrese el Usuario" required>
                      <div class="invalid-feedback">
                          El campo 'usuario' no puede ir vacío
                      </div>
                  </div>

                  <div class="form-group mb-3">
                      <label for="password" class="mb-3">Contraseña</label>
                      <input type="password" name="uspass" id="uspass" class="form-control inpEdit" placeholder="Ingrese la contraseña" required>
                      <div class="invalid-feedback">
                          El campo 'contraseña' no puede ir vacío
                      </div>
                  </div>

                  <div class="d-flex justify-content-end">
                      <input type="submit" class="btn btn-optimizado"  id="btnSubmit" value="Ingresar" style="border-color:black; border-radius: 0; background-color:white;color:black;"></input>
                  </div>
              </div>

          </form>
      </div>
  </div>
  
      


<?php include_once('../../Templates/footer.php') ?>

<script src="../../Assets/js/login.js"></script>
   

