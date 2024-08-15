const cerrar_carrito = document.getElementById('cerrar_carrito');
const modal_carrito = document.getElementById('myModalCasero');

// Función para cerrar el carrito
if (cerrar_carrito) {
  cerrar_carrito.addEventListener('click', () => {
    modal_carrito.style.display = 'none';
  });
}

const getAllCarrito = async () => {
  const tabla_carrito = document.getElementById('tabla_carrito');
  tabla_carrito.innerHTML = ''; // Limpiar el contenido de la tabla

  try {
    const response = await fetch('/libreria/View/carrito/action/get_carrito.php');

    // Verifica si la respuesta fue exitosa
    if (!response.ok) {
      throw new Error(`HTTP error! Status: ${response.status}`);
    }

    const data = await response.json();

    if (data.length > 0) {
      tabla_carrito.innerHTML = 'Tienes productos en el carrito.';
    } else {
      tabla_carrito.innerHTML = 'No se han agregado productos aún.';
    }

    modal_carrito.style.display = 'block'; // Mostrar el modal del carrito

  } catch (error) {
    console.error('Error al obtener el carrito:', error);
    tabla_carrito.innerHTML = 'Hubo un error al obtener los productos del carrito.';
    modal_carrito.style.display = 'block'; // Mostrar el modal del carrito en caso de error
  }
};

/*  Agregar producto  */
const inpCant = document.querySelectorAll('.inpCant');

const btnAdd = document.querySelectorAll('.btnAdd');

function addProduct(e) {
  e.previousSibling.value++;
  let form = new FormData();
  form.append('cantidad', e.previousSibling.value);
  form.append('id_compra_item', e.getAttribute('data-id'));
  form.append('id_producto', e.getAttribute('data-producto'));
  $.ajax({
    type: "POST",
    url: "../../View/carrito/action/carrito.php",
    data: form,
    processData: false,
    contentType: false,
    error: function (err, textStatus, xhr) {
      let errors = JSON.parse(err.responseText);
      errors = errors.join(". ");
      showErrorAlert(null, errors);
      e.previousSibling.value--;
    }
  })
}