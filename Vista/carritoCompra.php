<?php
include_once "../Estructura/header.php";
include_once "../Control/pagPublica.php";  
$data = data_submitted();

$carritoObj = new Carrito();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Procesar los datos POST
  if (isset($data['idproducto']) && isset($data['cantidad'])) {
    $carritoObj->agregarProducto($data);
  }

  // Redirigir a la misma página utilizando GET para evitar reenvío de datos al recargar
  header("Location: carritoCompra.php");
  exit();
}

// Obtener el contenido del carrito
$carrito = $carritoObj->obtenerCarrito();

// Si el carrito está vacío, redirigir al index o si entra por la URL
if (empty($carrito)) {
  header("Location: ../Vista/index.php");
  exit();
}
?>

<div class="ui horizontal divider"></div>
<div class="ui container segment">
  <h1 class="text-center">Carrito de Compras </h1>
  <table class="ui celled table">
    <thead>
      <tr>
        <th>Producto</th>
        <th>Precio</th>
        <th>Cantidad</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>

      <?php
      foreach ($carrito as $producto):
        $dirImgAct = $producto['idproducto'];
        $imgArtic = "img/Productos/{$dirImgAct}.jpg";
      ?>
        <tr>
          <td><?= $producto['nombre'] ?><br>
            <img src="<?= $imgArtic ?>" alt="Imagen del producto" style="max-width: 150px; max-height: 240px;">
          </td>
          <td><?= $producto['precio'] ?></td>
          <td>
            <span class="form-control"><?= $producto['cantidadproducto'] ?></span>
          </td>
          <td>
            <a href="../Control/ctrolEliminarProdCarrito.php?id=<?= $producto['idproducto'] ?>" class="btn-trash">
              <i class="trash alternate icon"></i> Eliminar
            </a>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <div class="d-flex gap-4">
    <button type="button" class="ui positive button" onclick="agregarCarrito(<?= $producto['idproducto']; ?>)">
      Comprar
    </button>
    <a href="./index.php" class="ui secondary button">
      Ver mas libros
    </a>
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
        if (result.respuesta) {
          window.location.href = "compraEstado.php";
        }
      }
    });
  }
</script>

</body>

</html>