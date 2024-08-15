<?php include_once('configuracion.php');

$objProducto = new Producto();
$productos = $objProducto->getProductos();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

  <title>
    Libreria
  </title>
  <style>
    .info-box {
      text-align: center;
      padding: 20px;
      border: 1px solid #ddd;
      background-color: #f9f9f9;
      border-radius: 8px;
    }

    .info-box i {
      font-size: 36px;
      color: #B36690;
      /* Color rosado */
    }

    .custom-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .custom-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>

<body>


  <?php include_once('Templates/header.php') ?>

  <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
     
    </div>
    <div class="carousel-inner" style="height: 600px;">
    <div class="carousel-item active">
    <img src="./assets/images/portada_cr.png" class="d-block w-100" alt="...">
    
    </div>
    <div class="carousel-item ">
    <img src="./assets/images/carusel.jpg" class="d-block w-100" alt="...">
    </div>

    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>





  <div class="container p-4">
   

    <h2 class="titulo p-4" style="text-align: center; font-size: 28px; font-family: 'Segoe UI';">LO MAS VENDIDO</h2>
    <!-- listar libros -->
    <div class="row">
      <?php if (!empty($productos)) : ?>
        <?php foreach ($productos as $p) : ?>
          <div class="col-3 mb-3">
            <div class="card  custom-card" style="width: 18rem; border: none;">
              <div class="card-body">
                <img src="./uploads/fotosproductos/<?= $p->getUrlImagen() ?>" class="card-img-top" alt="..." style="width: 100%; height: 400px;">
                <p class="card-title m-2" style="font-weight: bold;  color: #333;font-size: 14px; margin: bottom 8px;">
                  <span><?= strtoupper($p->getPronombre()) ?></span>
                </p>
                <p class="card-description m-2" style="font-size: 10px;">
                  <span><?= ucwords($p->getProdetalle()) ?></span>
                </p>
                <p class="card-text m-2">
                  <span>$<?= $p->getPrecio() ?></span>
                </p>
                  
                <div>
                  <?php if ($p->getProcantstock() > 0) : ?>
                    <button class="btn btn-primary w-100 add-to-cart" id="<?= $p->getIdProducto() ?>" style="border-color:black; border-radius: 4; background-color:white;color:black;">
                      Agregar al Carrito
                    </button>
                  <?php else : ?>
                    <p class="text-danger text-center">No hay stock disponible</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>



  </div>

  <?php include_once('Templates/footer.php') ?>
  <script src="Assets/js/carrito/cart.js"></script>

</body>

</html>