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

<div class="ui hidden divider"></div>
<div class="ui container grid center aligned segment">

  <div class="ui basic segment">
    <h2 class="center aligned">Tienda Lenny</h2>
    <h4>El Ateneo - lo que más te gusta de Libros Físicos con las mejores ofertas.</h4>
    <p>¡Encontrá promociones y descuentos en toda la tienda!</p>

  </div>


  <div id="seccion-productos" class="ui container grid center aligned">

    <?php if ($rolActual == 2) { ?>
      <div class="four wide column">

        <div class="ui  raised segment center aligned" style="min-height: 400px">
          <div class="ui hidden divider"></div>
          <div class="ui hidden divider"></div>
          <div class="ui hidden divider"></div>
          <h3 class="ui icon header"><a style="text-decoration: none;" href="nuevoProducto.php"><i class="plus circle icon"></i>Agregar Libro</a></h3>
        </div>
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
          <div class="four wide column raised segment">
            <div class="ui rised segment" style="min-height: 400px">
              <h3 class="ui header"><?= $producto->getProNombre() ?></h3>

              <!-- Verificar si la imagen existe antes de mostrarla -->
              <a href="./productoPag.php?id=<?= $producto->getIdProducto() ?>&nombrelibro=<?= $producto->getProNombre() ?>">
                <img src="<?= $imgArtic ?>" alt="<?= $detallesProAct ?>" style="max-width: 200px; max-height: 280px;">
              </a>

              <?php if ($producto->getProCantStock() == 0) { ?>
                <p class='ui red label'>Producto sin stock</p>
                <p class="gray">$<?= $producto->getProPrecio() ?></p>
              <?php } else { ?>
                <p class=''>$<?= $producto->getProPrecio() ?></p>
              <?php } ?>

              <?php if ($rolActual == 2) { ?>
                <div class="center aligned">
                  <a href="./nuevoProducto.php?idproducto=<?= $producto->getIdProducto() ?>" class="ui primary button">Editar</a>
                </div>
              <?php } ?>
            </div>
          </div>
      <?php }
      }
    } else { ?>
      <p class="center aligned">No hay productos disponibles en este momento.</p>
    <?php } ?>
  </div>
</div>

<?php include_once "../Estructura/footer.php"; ?>