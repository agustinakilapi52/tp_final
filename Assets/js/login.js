function iniciarSesion(e) {
    let form = new FormData(e);

    // Debugging: Verifica el contenido del formulario
    for (let pair of form.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    return $.ajax({
        type: "POST",
        url: "action/accion_verificar_login.php",
        data: form,
        processData: false,
        contentType: false,
        beforeSend: function() {
            console.log('Loading...');
        },
    });
}

document.getElementById('form_login').onsubmit = function(event) {
    event.preventDefault();
    const autorizarForm = this;
    iniciarSesion(autorizarForm)
        .done(function(data) {
            if (data) {
                window.location.replace('../../index.php');
            }
        })
        .fail((err) => {
            console.error('Error:', err);
            console.error('Response Text:', err.responseText);
            try {
                let errors = JSON.parse(err.responseText);
                errors = errors.join(". ");
                alert(errors);
            } catch (e) {
                alert("An error occurred while processing your request.");
            }
            document.getElementById('btnSubmit').disabled = false;
        });
}
