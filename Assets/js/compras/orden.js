// Click en el botón de cancelar el pedido
const btnCancelarPedido = document.getElementById("btnCancelarPedido");

if (btnCancelarPedido) {
  btnCancelarPedido.addEventListener("click", () => {
    let tipo = btnCancelarPedido.getAttribute("data-id");
    let form = new FormData();
    form.append("id_compra", id_compra);
    form.append("tipo", tipo);
    $.ajax({
      type: "POST",
      url: "action/cambiar_estado.php",
      data: form,
      processData: false,
      contentType: false,
    })
      .done(function (data) {
        window.location.replace(`${principal}View/compra/compras.php`);
      })
      .fail((err, textStatus, xhr) => {
        let errors = JSON.parse(err.responseText);
        errors = errors.join(". ");
        showErrorAlert(null, errors);
      });
  });
}

const btnEnviarPedido = document.getElementById("btnEnviarPedido");

if (btnEnviarPedido) {
  btnEnviarPedido.addEventListener("click", () => {
    let tipo = btnEnviarPedido.getAttribute("data-id");
    let form = new FormData();
    form.append("id_compra", id_compra);
    form.append("tipo", tipo);

    $.ajax({
      type: "POST",
      url: "action/cambiar_estado.php",
      data: form,
      processData: false,
      contentType: false,
    })
      .done(function (data) {
        window.location.replace(`${principal}View/compra/compras.php`);
      })
      .fail((err, textStatus, xhr) => {
        let errors = JSON.parse(err.responseText);
        errors = errors.join(". ");
      });
  });
}
