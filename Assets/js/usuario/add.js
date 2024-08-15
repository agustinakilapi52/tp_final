// Función para mostrar un mensaje de carga
function loadingAlert() {
    console.log('Cargando datos...'); // Mensaje de carga
}

// Función para cargar datos del formulario utilizando jQuery
function cargar(e, id_tipo_crear) {
    let form = new FormData(e); // Crear un FormData a partir del formulario
    form.append('id_tipo_crear', id_tipo_crear);
    
    return $.ajax({
        type: "POST",
        url: "action/crear_usuario.php",
        data: form,
        processData: false, // No procesar los datos
        contentType: false, // No establecer contentType
        beforeSend: function() {
            loadingAlert(); // Mostrar alerta de carga
        }
    });
}

// Evento submit del formulario
document.getElementById('formulario').onsubmit = function(event) {
    event.preventDefault(); // Evitar envío tradicional del formulario
    
    const btnSubmit = document.getElementById('btnSubmit');
    const autorizarForm = this;
    let id_tipo_crear = 3; // ID para crear un nuevo producto
    
    btnSubmit.disabled = true;
    
    cargar(autorizarForm, id_tipo_crear)
        .done(function(data) {
            console.log(data);
            alert("Carga Exitosa\n\nSe ha creado el producto correctamente.");
            window.location.replace(principal + "View/usuario/admin_usuario.php");
        })
        .fail(function(xhr, textStatus, errorThrown) {
            let errors = JSON.parse(xhr.responseText);
            errors = errors.join(". ");
            alert(errors);
            btnSubmit.disabled = false;
        });
}
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
