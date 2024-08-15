 <?php
  $objMenu = new Menu();
  $menu = $objMenu->getRolesParaMenu($MI_SESION->getUsuarioRolesLogueado());
  $menu = $objMenu->estructuraMenu($menu);
  ?>
 <!DOCTYPE html>
 <html lang="en">

 <head>
   <meta charset="utf-8" />
   <meta name="viewport" content="width=device-width, initial-scale=1" />
   <meta name="author" content="Untree.co" />
   <link href="<?= $PRINCIPAL ?>node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
   <meta name="description" content="" />
   <meta name="keywords" content="bootstrap, bootstrap5" />
    <link rel="stylesheet" href="<?= $PRINCIPAL ?>Assets/css/header.css">

  <title>
    libreria 
  </title>
 
  <style>
    .navbar-custom {
      background-color: #ffffff; 
      border-bottom: 1px solid #ccc;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); 
    }
  </style>
</head>

<body>
<!-- menu de navegacion -->
<nav class="navbar navbar-expand-lg navbar-custom">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= $PRINCIPAL ?>index.php">
    <img src="<?= $PRINCIPAL ?>Assets/images/logo.png" style="width: 100px; height: auto;" alt="...">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarScroll">
      <ul class="navbar-nav my-3 my-lg-0 navbar-nav-scroll gap-4 d-flex w-100" style="--bs-scroll-height: 100px;">
         <li class="nav-item ms-auto">
            <a class="btn btn-danger" href="<?= $PRINCIPAL ?>index.php" style="border: 0; border-radius: 0; background-color: #fff; color: #000;">INICIO</a>
           
          </li>
        <!-- Menú generado -->
        <?php if ($MI_SESION->validar()): ?>
          <?php echo $menu ?>
          <li class="nav-item ms-auto">
            <a class="btn btn-danger" href="<?= $PRINCIPAL ?>View/login/action/accion_cerrar_sesion.php" style="border: 1px solid #000; border-radius: 0; background-color: #fff; color: #000;">Cerrar Sesión</a>
            <a class="btn btn-danger" href="<?= $PRINCIPAL ?>View/perfil/miperfil.php" style="border: 1px solid #000; border-radius: 0; background-color: #fff; color: #000;">&#128100; Mi Perfil</a>
          </li>
        <?php else: ?>
          <li class="nav-item ms-auto">
            <a class="btn btn-primary" href="<?= $PRINCIPAL ?>View/login/login.php" style="border: 1px solid #000; border-radius: 0; background-color: #fff; color: #000;">Iniciar Sesion</a>
          </li>
        <?php endif; ?>
      </ul>

      <ul class="d-flex p-4" style="list-style-type: none;">
           <li class="nav-item">
             <div class="dropdown">

               <button class="dropdown-toggle btn_carrito" data-bs-toggle="dropdown">
               <i class="bi bi-cart" aria-expanded="true"></i>
               </button>

               <ul class="dropdown-menu p-2 prevent-close carrito" data-bs-popper="static">
                 <li class="prevent-close carrito_lista" id="items_carrito">
                   
                 </li>

                 <li class="carrito_total">
                   <div class="d-flex gap-3 justify-content-end">
                     <p class="mb-0">Total: </p>
                     <p class="mb-0 fw-semibold" id="total">$0</p>
                   </div>

                   <div class="justify-content-end mt-3 mb-1" id="confirmar_compra" style="display: none;">
                     <button class="btn_confirmar_compra" onclick="finalizarCompra()">Confirmar compra</button>
                   </div>
                 </li>
               </ul>
             </div>
           </li>
         </ul>
    </div>
  </div>
</nav>

   <!-- <script src="<?= $PRINCIPAL ?>Assets/js/header.js"></script>
<script src="<?= $PRINCIPAL ?>Assets/js/jquery-3.7.1.min.js"></script> -->
<!-- <script src="<?= $PRINCIPAL ?>Assets/js/bootstrap.bundle.min.js"></script>-->
</body>
   <!-- <script src="<?= $PRINCIPAL ?>Assets/js/bootstrap.bundle.min.js"></script> -->
 </body>

 <script>
   document.querySelectorAll('.prevent-close').forEach(function(element) {
     element.addEventListener('click', function(event) {
       event.stopPropagation(); // Evita que el evento de clic cierre el menú desplegable
     });
   });
 </script>