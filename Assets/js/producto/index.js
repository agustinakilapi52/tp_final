document.addEventListener('DOMContentLoaded', function() {
    const editImg = document.getElementById('edit_img');
    const imgAdd = document.getElementById('img_add');

    if (editImg) {
        editImg.addEventListener('change', function() {
            displayImage(this);
        });
    } else {
        console.error("El elemento con ID 'edit_img' no se encontró en el DOM.");
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
    }eeeetxxx
}

const tableContainer = document.getElementById('tableContainer');
let productos = [];

const getAllProductos = async () => {
    tableContainer.innerHTML = '';
    const response = await fetch(principal + 'View/producto/action/listaProducto.php');
    const data = await response.json();

    productos = data;

    data.forEach(e => {
        const tr = document.createElement('tr');
        console.log(e)
        let td = document.createElement('td');
        td.textContent = e.id_producto;
        tr.appendChild(td);

        td = document.createElement('td');
        const img = document.createElement('img');
        img.src = '../../uploads/fotosproductos/' + e.imgproducto;
        img.style.width = '100px';
        img.style.height = '150px';
        img.style.border = '2px solid white';
        td.appendChild(img);
        tr.appendChild(td);

        td = document.createElement('td');
        td.textContent = e.pronombre;
        tr.appendChild(td);

        td = document.createElement('td');
        td.textContent = e.prodetalle;
        tr.appendChild(td);

        td = document.createElement('td');
        // Verificar si el stock es igual a 0
        if (e.procantstock === 0) {
            td.textContent = 'No hay stock';
            td.style.color = 'red';
        } else {
            td.textContent = e.procantstock;
        }
        tr.appendChild(td);

        td = document.createElement('td');
        td.textContent = '$' + e.proprecio;
        tr.appendChild(td);

        td = document.createElement('td');
        td.style.textAlign = 'center';

        const btnModal = document.createElement('button');
        btnModal.className = 'btn btn-secondary btn-sm ms-2';
        btnModal.setAttribute('data-bs-toggle', 'modal');
        btnModal.setAttribute('data-bs-target', '#modal_edicion_producto');
        btnModal.setAttribute('data-id', e.id_producto);

        const icon = document.createElement('i');
        icon.className = 'bi bi-pencil';
        btnModal.appendChild(document.createTextNode(' Editar'));
        btnModal.appendChild(icon);
        btnModal.onclick = function() { modalEditUser(e.id_producto); };
        td.appendChild(btnModal);

        tr.appendChild(td);
        tableContainer.appendChild(tr);
    });
};

getAllProductos();

function modalEditUser(id_producto) {
    let producto = productos.find(p => p.id_producto == id_producto);

    if (!producto) {
        console.error(`Producto con ID ${id_producto} no encontrado.`);
        return;
    }

    const imgElement = document.getElementById('img_add');
    imgElement.src = '/tp-avanzada/uploads/fotosproductos/' + producto.imgproducto;

    document.getElementById('pronombre').value = producto.pronombre || '';
    document.getElementById('prodetalle').value = producto.prodetalle || '';
    document.getElementById('procantstock').value = producto.procantstock || '';
    document.getElementById('proprecio').value = producto.proprecio || '';
    document.getElementById('idproducto').value = producto.id_producto;

    let modal = new bootstrap.Modal(document.getElementById('modal_edicion_producto'));
    modal.show();
}

function cargarEdicionAjax(e) {
    return $.ajax({
        type: "POST",
        url: "action/editarProducto.php",
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
                window.location.replace(principal + "View/producto/admin-productos.php");
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
