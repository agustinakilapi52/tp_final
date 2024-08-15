document.addEventListener('DOMContentLoaded', function() {
    const editImg = document.getElementById('edit_img');
    const imgAdd = document.getElementById('img_add');
    const tableContainer = document.getElementById('tableContainer');

    if (editImg) {
        editImg.addEventListener('change', function() {
            displayImage(this);
        });
    } else {
        console.error("El elemento con ID 'edit_img' no se encontró en el DOM.");
    }

    if (tableContainer) {
        getAllUsuarios();
    } else {
        console.error("El elemento con ID 'tableContainer' no se encontró en el DOM.");
    }
});

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

async function getAllUsuarios() {
    const tableContainer = document.getElementById('tableContainer');
    if (!tableContainer) {
        console.error("El elemento con ID 'tableContainer' no se encontró en el DOM.");
        return;
    }

    tableContainer.innerHTML = '';

    try {
        const response = await fetch(principal + 'View/usuario/action/listar_usuario.php');
        const data = await response.json();

        usuarios = data;

        data.forEach(e => {
            const tr = document.createElement('tr');

            let td = document.createElement('td');
            td.textContent = e.id_usuario;
            tr.appendChild(td);

            td = document.createElement('td');
            const img = document.createElement('img');
            img.src = '../../uploads/fotosusuario/' + e.imgperfil;
            img.style.width = '100px';
            img.style.height = '100px';
            img.style.border = '2px solid white'; 
            img.style.borderRadius = '50%';
            img.style.objectFit = 'cover'; 
            td.appendChild(img);
            tr.appendChild(td);

            td = document.createElement('td');
            td.textContent = e.usnombre;
            tr.appendChild(td);

            td = document.createElement('td');
            td.textContent = e.ustelefono;
            tr.appendChild(td);

            td = document.createElement('td');
            td.textContent = e.usmail;
            tr.appendChild(td);

            td = document.createElement('td');
            if (e.estado && e.estado !== '0000-00-00 00:00:00') {
                td.textContent = 'Usuario desactivado';
                td.style.color = 'red'; 
            } else {
                td.textContent = 'Usuario activo';
            }
            tr.appendChild(td);

            td = document.createElement('td');
            td.style.textAlign = 'center';

            const btnModal = document.createElement('button');
            btnModal.className = 'btn btn-secondary btn-sm ms-2';
            btnModal.setAttribute('data-bs-toggle', 'modal');
            btnModal.setAttribute('data-bs-target', '#modal_edicion_usuario');
            btnModal.setAttribute('data-id', e.id_usuario);  // Reemplazar con id_usuario si es necesario

            const icon = document.createElement('i');
            icon.className = 'bi bi-pencil';

            btnModal.appendChild(document.createTextNode(' Editar'));
            btnModal.appendChild(icon);
            btnModal.onclick = function() { modalEditUser(e.id_usuario); };

            const btnDeactivate = document.createElement('button');
            btnDeactivate.className = 'btn btn-danger btn-sm ms-2'; 
            btnDeactivate.textContent = 'Desactivar';
            btnDeactivate.onclick = function() { deactivateUser(e.id_usuario); };

            td.appendChild(btnModal);
            td.appendChild(btnDeactivate);
            tr.appendChild(td);

            tableContainer.appendChild(tr);
        });
    } catch (error) {
        console.error('Error al obtener la lista de usuarios:', error);
    }
}

function modalEditUser(id_usuario) {
    let usuario = usuarios.find(p => p.id_usuario == id_usuario);

    if (!usuario) {
        console.error(`Usuario con ID ${id_usuario} no encontrado.`);
        return;
    }

    const imgElement = document.getElementById('img_add');
    imgElement.src = '/tp-avanzada/uploads/fotosusuario/' + usuario.imgperfil;

    document.getElementById('nombre').value = usuario.usnombre || ''; 
    document.getElementById('correo').value = usuario.usmail || '';
    document.getElementById('telefono').value = usuario.ustelefono || '';
    document.getElementById('idusuario').value = usuario.id_usuario; 

    let modal = new bootstrap.Modal(document.getElementById('modal_edicion_usuario'));
    modal.show();
}

function cargarEdicionAjax(e) {
    return $.ajax({
        type: "POST",
        url: "action/editar_usuario.php",
        data: new FormData(e),
        processData: false,
        contentType: false,
        beforeSend: function () {
            console.log('Loading...');
        },
    });
}

document.getElementById('formulario').onsubmit = function(event) {
    event.preventDefault();
    const btnSubmit = document.getElementById('btnSubmit');
    const autorizarForm = this;
    btnSubmit.disabled = true;

    if (confirm('¿Confirma la carga del producto?')) {
        cargarEdicionAjax(autorizarForm)
            .done(data => {
                console.log(data);
                alert('Se ha creado el producto correctamente');
                window.location.replace(principal + "View/usuario/admin_usuario.php");
            })
            .fail(err => {
                let errors = JSON.parse(err.responseText);
                errors = errors.join(". ");
                alert('Error: ' + errors);
                btnSubmit.disabled = false;
            });
    } else {
        btnSubmit.disabled = false;
    }
};

function deactivateUser(id_usuario) {
    if (confirm('¿Estás seguro de que deseas desactivar este usuario?')) {
        fetch(principal + 'View/usuario/action/desactivar_usuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id_usuario: id_usuario, estado: 0 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Usuario desactivado correctamente');
                getAllUsuarios();
            } else {
                alert('Hubo un error al desactivar el usuario');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
