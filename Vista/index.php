<?php

include_once '../configuracion.php';
$session = new Session();
$rolActual = $session->getRol();

$objProducto = new AbmProducto();
$arrayProductos = $objProducto->buscar(NULL);

?>
<!DOCTYPE html>
<?php include_once "../Estructura/header.php"; ?>
<link rel="stylesheet" href="Assets/css/style.css">


<div class="container mb-5">
  <h2 class="mt-5">Tienda Lenny</h2>
  <h4 class=""> El Ateneo lo que más te gusta de Libros Físicos con las mejores ofertas.</h4>
  <p>¡Encontrá promociones y descuentos en toda la tienda!</p>
</div>


<div id="seccion-productos" class="container productos d-flex flex-wrap">

  <div class="container d-flex flex-wrap justify-content-center">
    <?php if ($rolActual == 2) { ?>
      <div class="sombra-caja border item-box m-5 d-flex flex-column align-items-center justify-content-center" style="min-width: 282px; min-height: 580px">
        <h5 class="mb-5">Agregar Libro</h5>
        <a href="nuevoLibro.php">
          <i class="bi bi-plus-circle" style="font-size: 8rem;"></i>
        </a>
      <?php } ?>
      </div>
      <?php
      foreach ($arrayProductos as $producto) {
          if (($producto->getProCantStock() >= 0)) {
            $detallesProAct = $producto->getProDetalle();
            $dirImgAct = $producto->getIdProducto();
            $imgArtic ="{$ROOT}Vista/img/Productos/{$dirImgAct}"
      ?>
              <h3 class="p-3 text-center"><?= $producto->getProNombre() ?></h3>
              <a href="./productoPag.php?id=<?= $producto->getIdProducto() ?>&nombrecel=<?= $producto->getProNombre() ?>">
              

              <?php if ($producto->getProCantStock() == 0) { ?>
                <p class='fw-bold text-danger'>Producto sin stock</span></p>
              

                <p class="text-nowrap">
                  <span class='text-muted text-decoration-line-through'>$<?=  $producto->getProPrecio()  ?></span>
            
                </p>

              <?php } else { ?>
                <p class='fs-4'>$<?= $producto->getProPrecio()  ?> </p>
              <?php } ?>

              <?php if ($rolActual == 2) { ?>
                <div class="d-flex">
                  <a href="./nuevoProducto.php?id=<?= $producto->getIdProducto() ?>" class="btn btn-primary mx-1 mb-2">Editar</a>
                </div>
              <?php } ?>

            </div>
      <?php }}?>
  </div>

</div>

<?php include_once "./includes/footer.php"; ?>



