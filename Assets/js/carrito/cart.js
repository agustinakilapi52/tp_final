$(document).ready(async function () {
  // Cuando el documento esta listo, obtengo los productos del carrito
  await obtenerProductosCarrito();

  // Manejar clic en botón "Agregar al Carrito"
  $(".add-to-cart").click(function () {
    // Obtengo el ID del producto
    let productId = this.id;
    if (productId) {
      agregarCompraCarrito(productId)
        .done(function (response) {
          let rta = JSON.parse(response);
          console.log(rta);
          if (rta.exito) {
            alert("Producto agregado al carrito");
            // Si todo se agrego correctamente, actualizo el carrito del usuario
            obtenerProductosCarrito();
          } else {
            alert(rta.error);
          }
        })
        .fail(function () {
          alert("No hay mas stock disponible de este producto.");
          // Busca el elemento con el producId y le cambia el html al elemento padre
          $(`#${productId}`)
            .parent()
            .html(
              `<p class="text-danger text-center">No hay stock disponible</p>`
            );
        });
    }
  });
});

/**
 * Inicia la compra de un producto y lo agrega al carrito con AJAX
 */
function agregarCompraCarrito(id_producto) {
  let form = new FormData();
  form.append("id_producto", id_producto);
  return $.ajax({
    type: "POST",
    url: "View/carrito/action/add_carrito.php",
    data: form,
    processData: false,
    contentType: false,
  });
}

/**
 * Obtiene los productos que el usuario tiene en su carrito
 */
async function obtenerProductosCarrito() {
  const response = await fetch("View/carrito/action/get_carrito.php");
  const data = await response.json();
  let items_carritos = document.getElementById("items_carrito");
  items_carritos.innerHTML = "";
  let total = 0;
  if (data.length > 0) {
    data.forEach((producto) => {
      let precioCantidad = producto.proprecio * producto.cantidad;
      document.querySelector(".btn_confirmar_compra").id = producto.id_compra;

      total += precioCantidad;
      // Creo los productos y los agrego al carrito
      items_carritos.innerHTML += `
      <article id="itemCompra${producto.id_compraitem}" class="dropdown-item d-flex align-items-center justify-content-between prevent-close">
          <div class="d-flex align-items-center justify-content-between gap-3">
              <div class="img_producto_carrito">
                  <img src="uploads/fotosproductos/${producto.img_producto}" alt="Imagen del producto de compra de libros">
              </div>
  
              <div>
                  <p class="mb-0">${producto.pronombre}</p>
                  <p class="mb-0 fw-semibold">$${precioCantidad}</p>
                  <div class="d-flex align-items-center">
                      <button type="button" class="btn btn-outline-secondary btn-sm me-2" onclick="sacandoProducto(${producto.id_compraitem}, ${producto.id_producto})">-</button>
                      <span id="cantidad_actual_${producto.id_compraitem}">${producto.cantidad}</span>
                      <button type="button" class="btn btn-outline-secondary btn-sm ms-2" onclick="agregarProducto(${producto.id_compraitem}, ${producto.id_producto})">+</button>
                  </div>
              </div>
          </div>
  
      </article>
      `;
    });
    document.getElementById("confirmar_compra").style.display = "flex";
  } else {
    // En caso de que no haya nada, agrego un texto indicando que no hay productos
    items_carritos.innerHTML = `
    <article class="dropdown-item d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center justify-content-between gap-3">
            <div>
                <p class="mb-0">No hay productos en el carrito</p>
            </div>
        </div>
    </article>
    `;
    document.getElementById("confirmar_compra").style.display = "none";
    document.querySelector(".btn_confirmar_compra").id = "";
  }
  document.getElementById("total").textContent = "$" + total;
  console.log(data);
}

/**
 * Agregar un producto mas cuando este ya esta en el carrito
 */
function agregarProducto(id_compraitem, id_producto) {
  let cantidad_actual_element = document.getElementById(
    `cantidad_actual_${id_compraitem}`
  );
  let cantidad_actual = parseInt(cantidad_actual_element.textContent);
  cantidad_actual_element.textContent = cantidad_actual + 1;

  let form = new FormData();
  form.append("id_compra_item", id_compraitem);
  form.append("cantidad", cantidad_actual + 1);
  form.append("id_producto", id_producto);
  form.append("tipo", 1);
  $.ajax({
    type: "POST",
    url: "View/carrito/action/actualizar_carrito.php",
    data: form,
    processData: false,
    contentType: false,
    error: function (err, textStatus, xhr) {
      let errors = JSON.parse(err.responseText);
      errors = errors.join(". ");
      cantidad_actual_element.textContent = cantidad_actual;
      alert(errors);
      // Busca el elemento con el producId y le cambia el html al elemento padre
      $(`#${id_producto}`)
        .parent()
        .html(`<p class="text-danger text-center">No hay stock disponible</p>`);  
    },
  });
  calcularTotal();
}

/**
 * Elimina un producto cuando este ya esta en el carrito
 */
function sacandoProducto(id_compraitem, id_producto) {
  let cantidad_actual_element = document.getElementById(
    `cantidad_actual_${id_compraitem}`
  );
  let cantidad_actual = parseInt(cantidad_actual_element.textContent);
  if (cantidad_actual > 1) {
    cantidad_actual_element.textContent = cantidad_actual - 1;
  } else if (cantidad_actual === 1) {
    document.getElementById(`itemCompra${id_compraitem}`).remove();

    if (document.getElementById("items_carrito").childElementCount === 0) {
      document.getElementById("items_carrito").innerHTML = `
      <article class="dropdown-item d-flex align-items-center justify-content-between">
          <div class="d-flex align-items-center justify-content-between gap-3">
              <div>
                  <p class="mb-0">No hay productos en el carrito</p>
              </div>
          </div>
      </article>`;
    }
  }

  let form = new FormData();
  form.append("id_compra_item", id_compraitem);
  form.append("cantidad", cantidad_actual - 1);
  form.append("id_producto", id_producto);
  form.append("tipo", 0);
  $.ajax({
    type: "POST",
    url: "View/carrito/action/actualizar_carrito.php",
    data: form,
    processData: false,
    contentType: false,
    error: function (err, textStatus, xhr) {
      let errors = JSON.parse(err.responseText);
      errors = errors.join(". ");
      cantidad_actual_element.textContent = cantidad_actual;
      alert(errors);
    },
  })
  calcularTotal();
}

/**
 * Calcula el total de todos los productos agregados al carrito
 */
async function calcularTotal() {
  const response = await fetch("View/carrito/action/get_carrito.php");
  const data = await response.json();
  let total = 0;
  console.log("Data:", data);
  data.forEach((producto) => {
    let precioCantidad = producto.proprecio * producto.cantidad;
    total += precioCantidad;
  });
  document.getElementById("total").textContent = "$" + total;
}

function finalizarCompra() {
  let id_compra = document.querySelector(".btn_confirmar_compra").id;
  let form = new FormData();
  form.append("id_compra", id_compra);
  $.ajax({
    type: "POST",
    url: "View/carrito/action/finalizar_compra.php",
    data: form,
    processData: false,
    contentType: false,
  });
  obtenerProductosCarrito();
}
