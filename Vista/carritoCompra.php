<?php
include_once "../configuracion.php";
$data = data_submitted();

$carritoObj = new Carrito();

// $pageReloaded = "<script>document.write(sessionStorage.getItem('pageReloaded'));</script>";
//&& $pageReloaded === 'false'
if (isset($data['idproducto']) && isset($data['cantidad'])) {
  $carritoObj->agregarProducto($data);
}
// Obtener el contenido del carrito
$carrito = $carritoObj->obtenerCarrito();

$controlStock = true;

$abmProducto = new AbmProducto();


?>
<!DOCTYPE html>
<html lang="en">
<?php include_once "../Estructura/header.php"; ?>


<div class="ui horizontal divider"></div>
<div class="ui very padded segment sixteen wide column">

  <h1 class="text-center">Carrito de Compras</h1>
  <div class="row">
    <?php
    if (!empty($carrito)) {
      foreach ($carrito as $productoCarrito) {
        $dirImgAct = $productoCarrito['idproducto'];
        $imgArtic = "img/Productos/{$dirImgAct}.jpg";
        $productoActual = $abmProducto->buscar(['idproducto' => $productoCarrito['idproducto']])[0];

    ?>
        <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center my-3">
          <h3 class="p-3"><?= $productoCarrito['nombre'] ?></h3>

          <!-- Verificar si la imagen existe antes de mostrarla -->
          <a href="./productoPag.php?id=<?= $productoCarrito['idproducto'] ?>&nombrelibro=<?= $productoCarrito['nombre']  ?>">
            <img src="<?= $imgArtic ?>" alt="Imagen del producto" style="max-width: 200px; max-height: 280px;">
          </a>

          <p class='fs-4'>$<?= $productoCarrito['precio'] ?></p>
          <p class='fs-4'>Cantidad: <?= $productoCarrito['cantidadproducto'] ?></p>

        </div>
      <?php

        if ($productoActual->getProCantStock() < $productoCarrito['cantidadproducto']) {
          $controlStock = false;
        }
      } ?>

      <!-- Botones -->

      <button type="submit" class=" btn btn-success my-2">
        <a onclick="<?php

                    if ($controlStock) { ?>
          agregarCarrito(<?php $productoCarrito['idproducto'] ?>);
          <?php } ?>
        ">Comprar
        </a>
      </button>

      <button type="submit" class=" btn btn-success my-2">
        <a onclick="limpiarCarrito()">Limpiar Carrito
        </a>
      </button>

    <?php
    } else { ?>
      <p class="text-center">No hay productos agregados al carrito.</p>
    <?php } ?>
  </div>
</div>

<script type="text/javascript">
  function agregarCarrito(idProducto) {
    $.ajax({
      url: "Accion/procesarCompra.php",
      type: "POST",
      data: {
        id: idProducto
      },
      success: function(result) {
        var result = JSON.parse(result);
        alert(result.mensaje);
        if (result.respuesta) {
          window.location.href = "index.php";
        }
      }
    });
  }

  function limpiarCarrito() {
    $.ajax({
      url: "Accion/procesarCompra.php",
      type: "POST",
      data: {
        limpiar: true
      },
      success: function(result) {
        var result = JSON.parse(result);
        alert(result.mensaje);
        if (result.respuesta) {
          window.location.href = "index.php";
        }
      }
    });
  }
</script>


</html>