// Función para mostrar una alerta de carga utilizando jQuery
function loadingAlert() {
    console.log('Cargando datos...'); // Ejemplo básico: mostrar mensaje en la consola
}

// Función para cargar datos del formulario
function cargar(e, id_tipo_crear) {
    let form = new FormData(e);
    form.append('id_tipo_crear', id_tipo_crear);
    
    return $.ajax({
        type: "POST",
        url: "action/crearProducto.php",
        data: form,
        processData: false,
        contentType: false,
        beforeSend: function() {
            loadingAlert(); // Mostrar alerta de carga
        }
    });
}

// Validar si hay campos vacíos
function validarFormulario(form) {
    let esValido = true;
    $(form).find('input[required], textarea[required]').each(function() {
        if ($(this).val().trim() === '') {
            esValido = false;
            $(this).addClass('is-invalid'); // Añadir clase para resaltar campo vacío
            console.error(`El campo "${$(this).attr('name')}" no puede estar vacío.`);
        } else {
            $(this).removeClass('is-invalid'); // Remover clase si el campo está lleno
        }
    });
    return esValido;
}

// Evento submit del formulario
document.getElementById('formulario').onsubmit = function(event) {
    event.preventDefault(); // Evitar envío tradicional del formulario
    
    const btnSubmit = document.getElementById('btnSubmit');
    const autorizarForm = this;
    let id_tipo_crear = 3; // ID para crear un nuevo producto

    if (!validarFormulario(autorizarForm)) {
        alert("Por favor, complete todos los campos requeridos.");
        return; // Salir si hay campos vacíos
    }

    btnSubmit.disabled = true;
    
    cargar(autorizarForm, id_tipo_crear)
        .done(function(data) {
            console.log(data);
            alert("Carga Exitosa\n\nSe ha creado el producto correctamente.");
            window.location.replace(principal + "View/producto/admin-productos.php");
        })
        .fail(function(xhr, textStatus, errorThrown) {
            let errors = JSON.parse(xhr.responseText);
            errors = errors.join(". ");
            alert(errors);
            btnSubmit.disabled = false;
        });
};

// Función para mostrar la imagen cargada
function displayImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgAdd = document.getElementById('img_add');
            imgAdd.src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Cargar nueva imagen de producto
const changeImg = document.getElementById('edit_img');
changeImg.addEventListener('click', () => {
    let edit_img = changeImg.previousElementSibling.getAttribute('id');
    edit_img = document.getElementById(edit_img); 
    edit_img.click();
});
