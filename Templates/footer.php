<div class="footer" style="background-color: #8B77A6; width: 100%;">
  <div class="container" style="display: flex; flex-direction: column; align-items: start; padding: 20px;">
    <h3 class="text-white" style="font-size: 18px; margin: 0 0 10px 0;">CONTACTO</h3>
    <p class="text-white" style="margin: 0;">
      <strong>Correo Electrónico:</strong> <a href="mailto:ejemplo@dominio.com" class="text-white" style="text-decoration: none;">libreria@gmail.com</a>
    </p>
    <p class="text-white" style="margin: 0;">
      <strong>Teléfono:</strong> <a href="tel:+1234567890" class="text-white" style="text-decoration: none;">+123 456 7890</a>
    </p>
  </div>
</div>
<script>
    (function () {
    'use strict'
    
    // Obtener todos los formularios que queremos validar
    var forms = document.querySelectorAll('.needs-validation')

    // Evitar la validación de formularios en el envío
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }

            form.classList.add('was-validated')
        }, false)
    })
})()
</script>
<script src="<?= $PRINCIPAL ?>Assets/js/header.js"></script>
<script src="<?= $PRINCIPAL ?>Assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= $PRINCIPAL ?>Assets/js/jquery-3.7.1.min.js"></script>
