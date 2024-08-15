const tableContainer = document.getElementById('tableContainer');
let menus = [];

const getAllMenusHistorico = async () => {
    try {
        const response = await fetch(principal + 'View/menu/action/listaMenu.php');
        if (!response.ok) {
            throw new Error('Error fetching data');
        }
        const data = await response.json();
        menus = data; // Asignar los menús obtenidos a la variable global
        renderTable(menus);
    } catch (error) {
        console.error('Error:', error);
    }
};

const renderTable = (data) => {
    tableContainer.innerHTML = ''; // Limpiar contenido anterior de la tabla
  
    data.forEach(e => {
        const tr = document.createElement('tr');

        const tdId = document.createElement('td');
        tdId.textContent = e.idmenu;
        tr.appendChild(tdId);

        const tdNombre = document.createElement('td');
        tdNombre.textContent = e.menombre;
        tr.appendChild(tdNombre);

        const tdRuta = document.createElement('td');
        tdRuta.textContent = e.medescripcion;
        tr.appendChild(tdRuta);

        const tdMenuPadre = document.createElement('td');
        tdMenuPadre.textContent = e.menu_padre || 'N/A';
        tr.appendChild(tdMenuPadre);

        const tdEstado = document.createElement('td');
        if (e.estado && e.estado !== '0000-00-00 00:00:00') {
            tdEstado.textContent = 'Desactivado';
            tdEstado.style.color = 'red'; // Opcional: Cambiar color del texto para resaltar
        } else {
            tdEstado.textContent = 'Activo';
        }
        tr.appendChild(tdEstado);
    
        const tdAcciones = document.createElement('td');
        tdAcciones.style.textAlign = 'center';

        const btnModal = document.createElement('button');
        btnModal.className = 'btn btn-secondary btn-sm ms-2';
        btnModal.setAttribute('data-bs-toggle', 'modal');
        btnModal.setAttribute('data-bs-target', '#modal_edicion_menu');
        btnModal.setAttribute('data-id', e.idmenu);
        
        const icon = document.createElement('i');
        icon.className = 'bi bi-pencil';
    
        btnModal.appendChild(document.createTextNode(' Editar'));
        btnModal.appendChild(icon);
        btnModal.onclick = function() { modalEdit(e.idmenu); };

        const btnDeactivate = document.createElement('button');
        btnDeactivate.className = 'btn btn-danger btn-sm ms-2'; 
        btnDeactivate.textContent = 'Desactivar';

        btnDeactivate.onclick = function() { deactivate(e.idmenu); };

        tdAcciones.appendChild(btnModal);
        tdAcciones.appendChild(btnDeactivate);
        tr.appendChild(tdAcciones);

        tableContainer.appendChild(tr);
    });
};

getAllMenusHistorico();
const roles = document.querySelectorAll('.checked_grupo');
const no_checked_grupo = document.querySelectorAll('.no_checked_grupo');
    
function modalEdit(idmenu) {
    let menu = menus.find(m => m.idmenu == idmenu);

    if (!menu) {
        console.error(`Menu con ID ${idmenu} no encontrado.`);
        return;
    }

    document.getElementById('menombre').value = menu.menombre || ''; 
    document.getElementById('medescripcion').value = menu.medescripcion || '';
    document.getElementById('idmenu').value = menu.idmenu; 
    document.getElementById('idpadre').value = menu.id_padre || '';

    
    
    if (Array.isArray(menu.roles)) {
        menu.roles.forEach(rol => {
            for (let i = 0; i < roles.length; i++) {
                if (roles[i].id == rol) {
                    roles[i].checked = true;
                    check_grupo[i].style.visibility = 'visible';
                }
            }
        });
    } else {
        console.error('roles no es un array o está undefined');
    }

    let modal = new bootstrap.Modal(document.getElementById('modal_edicion_menu'));
    modal.show();
}


for (let i = 0; i < roles.length; i++) {
    roles[i].addEventListener('click', () => {
        if (roles[i].checked) {
            check_grupo[i].style.visibility = 'visible';
            no_checked_grupo[i].checked = false;
        } else {
            no_checked_grupo[i].checked = true;
            no_checked_grupo[i].value = roles[i].value;
            check_grupo[i].style.visibility = 'hidden';
        }
    });
}

function cargarEdicionAjax(e) {
    return $.ajax({
        type: "POST",
        url: "action/editar_menu.php",
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
    const btnSubmit = document.getElementById('btnSubmitMenu'); // Cambiado a btnSubmitMenu
    const autorizarForm = this;
    btnSubmit.disabled = true;

    if (confirm('¿Confirma la carga del producto?')) {
        cargarEdicionAjax(autorizarForm)
            .done(data => {
                console.log(data);
                alert('Se ha creado el producto correctamente');
                window.location.replace(principal + "View/menu/admin_menu.php");
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


function deactivate(idmenu) {
    if (confirm('¿Estás seguro de que deseas desactivar el menu?')) {
        fetch(principal + 'View/menu/action/desactivar_menu.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ idmenu: idmenu, estado: 0 })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('desactivado correctamente');
                getAllUsuarios();
            } else {
                alert('Hubo un error al desactivar');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
