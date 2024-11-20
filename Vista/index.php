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
  <h4>El Ateneo - lo que más te gusta de Libros Físicos con las mejores ofertas.</h4>
  <p>¡Encontrá promociones y descuentos en toda la tienda!</p>
</div>

<div id="seccion-productos" class="container productos d-flex flex-wrap" >

  <?php if ($rolActual == 2) { ?>
    <div class="sombra-caja border item-box m-5 d-flex flex-column align-items-center justify-content-center" style="min-width: 282px; min-height: 580px">
      <h5 class="mb-5"><a href="nuevoLibro.php"><i class="bi bi-plus-circle" style="font-size: 8rem;"></i>Agregar Libro</a></h5>   
      
    </div>
  <?php } ?>

  <?php
  if (!empty($arrayProductos)) {
    foreach ($arrayProductos as $producto) {
      if ($producto->getProCantStock() >= 0) {
        $detallesProAct = $producto->getProDetalle();
        $dirImgAct = $producto->getIdProducto();
        $imgArtic = "img/Productos/{$dirImgAct}.jpg";
        ?>
        <div class="d-flex flex-column align-items-center justify-content-center text-center m-3" style="min-width: 300px;">
          <h3 class="p-3"><?= $producto->getProNombre() ?></h3>
          
          <!-- Verificar si la imagen existe antes de mostrarla -->
          <a href="./productoPag.php?id=<?= $producto->getIdProducto() ?>&nombrelibro=<?= $producto->getProNombre() ?>">
            <img src="<?= $imgArtic ?>" alt="<?= $detallesProAct ?>" style="max-width: 200px; max-height: 280px;">
          </a>

          <?php if ($producto->getProCantStock() == 0) { ?>
            <p class='fw-bold text-danger'>Producto sin stock</p>
            <p class="text-muted text-decoration-line-through">$<?= $producto->getProPrecio() ?></p>
          <?php } else { ?>
            <p class='fs-4'>$<?= $producto->getProPrecio() ?></p>
          <?php } ?>

          <?php if ($rolActual == 2) { ?>
            <div class="d-flex">
              <a href="./nuevoProducto.php?idproducto=<?= $producto->getIdProducto() ?>" class="btn btn-primary mx-1 mb-2">Editar</a>
            </div>
          <?php } ?>
        </div>
      <?php }
    }
  } else { ?>
    <p class="text-center">No hay productos disponibles en este momento.</p>
  <?php } ?>

</div>

<?php include_once "../Estructura/footer.php"; ?>
