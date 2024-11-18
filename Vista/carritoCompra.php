<?php
include_once "../configuracion.php";
$data = data_submitted();

$carritoObj = new Carrito();

if (isset($data['idproducto']) && isset($data['cantidad'])) {
  $carritoObj->agregarProducto($data);
}

// Obtener el contenido del carrito
$carrito = $carritoObj->obtenerCarrito();
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once "../Estructura/header.php"; ?>

<body>
  <div class="ui horizontal divider"></div>
  <div class="ui very padded segment sixteen wide column">

    <h1 class="text-center">Carrito de Compras</h1>
    <div class="row">
      <?php
      if (!empty($carrito)) {
        foreach ($carrito as $producto) {
          $dirImgAct = $producto['idproducto'];
          $imgArtic = "img/Productos/{$dirImgAct}.jpg";
      ?>
          <div class="col-md-4 d-flex flex-column align-items-center justify-content-center text-center my-3">
            <h3 class="p-3"><?= $producto['nombre'] ?></h3>

            <!-- Verificar si la imagen existe antes de mostrarla -->
            <a href="./productoPag.php?id=<?= $producto['idproducto'] ?>&nombrelibro=<?= $producto['nombre']  ?>">
              <img src="<?= $imgArtic ?>" alt="Imagen del producto" style="max-width: 200px; max-height: 280px;">
            </a>

            <p class='fs-4'>$<?= $producto['precio'] ?></p>

            <!-- Botón Comprar -->

            <button type="submit" class=" btn btn-success my-2">
              <a onclick="agregarCarrito(<?= $producto['idproducto']; ?>)">Comprar
              </a>
            </button>

          </div>
        <?php }
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
  </script>

</body>

</html>