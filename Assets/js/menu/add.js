const submenuCheck = document.getElementById('submenu');
const containerIdPadre = document.getElementById('containerIdPadre');

submenuCheck.addEventListener('change', () => {
    if (submenuCheck.checked) {
        containerIdPadre.style.display = 'block';
    } else {
        containerIdPadre.style.display = 'none';
    }
});
function loadingAlert() {
    // Puedes implementar aquí una lógica para mostrar una alerta de carga, como un modal o una animación
    console.log('Cargando datos...'); // Ejemplo básico: mostrar mensaje en la consola
}

function cargar(e, id_tipo_crear) {
    let form = new FormData(e);
    form.append('id_tipo_crear', id_tipo_crear);
    
    return $.ajax({
        type: "POST",
        url: "action/crearmenu.php",
        data: form,
        processData: false,
        contentType: false,
        beforeSend: function() {
            loadingAlert(); // Mostrar alerta de carga
        }
    });

}    
// Evento submit del formulario
document.getElementById('formulario_menu').onsubmit = function(event) {
    event.preventDefault(); // Evitar envío tradicional del formulario
    
    const btnSubmit = document.getElementById('btnSubmit');
    const autorizarForm = this;
    let id_tipo_crear = 3; // ID para crear un nuevo producto
    
    btnSubmit.disabled = true;
    
    cargar(autorizarForm, id_tipo_crear)
        .done(function(data) {
            console.log(data);
            alert("Carga Exitosa\n\nSe ha creado  correctamente.");
            window.location.replace(principal + "View/menu/admin_menu.php");
        })
        .fail(function(xhr, textStatus, errorThrown) {
            let errors = JSON.parse(xhr.responseText);
            errors = errors.join(". ");
            alert(errors);
            btnSubmit.disabled = false;
        });


}
