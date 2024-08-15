document.addEventListener('DOMContentLoaded', function() {
  const editImg = document.getElementById('edit_img');
  const imgAdd = document.getElementById('img_add');


  console.log('editImg:', editImg);
  console.log('imgAdd:', imgAdd);


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
  }
}

// Cuando escucha por el submit del formulario actualiza el perfil del usuario
document.getElementById("form_actualizar").onsubmit = function (event) {
  event.preventDefault();
  const autorizarForm = this;
  editar(autorizarForm)
      .done(function (data) {
          window.location.reload();
      })
      .fail((err, textStatus, xhr) => {
          let errors = JSON.parse(err.responseText);
          errors = errors.join(". ");
          alert(errors);
      });
};

/**
* Realiza la consulta AJAX para actualizar el perfil del usuario
*/
function editar(e) {
  let form = new FormData(e);
  return $.ajax({
      type: "POST",
      url: "action/actualizar_perfil.php",
      data: form,
      processData: false,
      contentType: false,
  });
}
