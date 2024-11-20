<?php
include_once "../Estructura/header.php";
$data = data_submitted();
$session = new Session;
if (isset($data["compraexitosa"]) && $data["compraexitosa"]) {
?>
      <div class="bg-success text-white">Compra realizada exitosamente</div>
    <?php }else{?>
        <div class="bg-danger text-white">No se pudo realizar su compra</div>


<?php }
$abmEstadoTipo = new ABMcompraEstadoTipo;
$abmCompraItem = new AbmCompraItem();
?>
<!DOCTYPE html>
<html lang="en">
<?include_once "../Estructura/header.php";?>
<h2 class="container my-5">Estado de Compra</h2>
<?php
$controlCompra= new ControlCompra;
$compras = $controlCompra->buscarCompras($session);
foreach($compras as $compra){
  $precioTotal=0;
    $idCompra = $compra->getidcompra();
    $paramIdCompra['idcompra'] = $idCompra;
    $ambCompraEstado =  new AbmCompraEstado;
    $paramIdCompra["idcompra"] = $compra->getIdCompra();
    $estado = $ambCompraEstado->buscar($paramIdCompra);    
    $arrItems = $abmCompraItem->buscar($paramIdCompra);
?>
<div class="container d-flex">
<div class="w-50 d-flex flex-column">
    <h3>Identificador de Compra: <?= $compra->getIdCompra(); ?></h3>
    <?php
      if ($session->getRol() == 2 && ($compra->getObjUsuario() != $session->getRol() || $estado != null)) {
        echo "<span class='mb-2 badge bg-primary'>ID Usuario: {$compra->getObjUsuario()}</span>
                ";
      }
      ?>
      <h4 class="fw-bold">Estado Actual: <?php
      $idTipoEstado["idcompraestadotipo"]= $estado[0]->getobjCompraEstadoTipo()->getidcompraestadotipo();
      $tipoEstado = $abmEstadoTipo->buscar($idTipoEstado);
       echo ($tipoEstado[0]->getCetDescripcion());?>
      </h4>
      <h4>Productos: </h4>
        <?php
        foreach($arrItems as $item){
          $idProducto= $item->getobjProducto()->getProNombre() . " ({$item->getCiCantidad()})";
        ?>
        <div class="d-flex flex-column">
            <p><?= $idProducto;?></p>
           <?php  $precioTotal += $item->getObjProducto()->getProPrecio();} ?>
           <p>
           <?php 
     echo "Total: $" . $precioTotal; ?>
           </p>
          </div>
      
</div>

<?php

if ($session->getRol() == 2 && $idTipoEstado["idcompraestadotipo"] != 4) {

?>
  <div class="d-flex flex-column justify-content-center align-items-center w-25">
    <form class="d-flex flex-column justify-content-center align-items-center form-cambiar-estado" data-id="<?= $compra->getIdCompra();?>" action="accion/cambiarEstado.php" method="POST">
      <select class="form-select" name="nuevoestado">
        <option selected>Cambiar estado</option>
        <option value="1">Iniciada</option>
        <option value="2">Aceptada</option>
        <option value="3">Enviada</option>
      </select>
      <input type="text" style="display: none;" value="<?= $compra->getIdCompra(); ?>" name="idcompra">
      <button class="btn btn-primary mt-3" type="submit">Confirmar cambio</button>
    </form>
  </div>
  <?php } ?>
<div class="ms-5 d-flex justify-content-center align-items-center">
  <form method="POST" action="accion/cancelarCompra.php" class="form-cancelar-compra" data-id="<?= $compra->getIdCompra(); ?>" >
    <input class="" style="display: none;" type="number" name="idcompracancelar" for="idcompracancelar" value='<?= $compra->getIdCompra() ?>'>
    <button class="btn btn-danger" type="submit" <?php if ($idTipoEstado["idcompraestadotipo"] == 4) { ?> disabled <?php } ?>>Cancelar Compra</button>
  </form>
</div>
</div>
<?php }?>
<script type="text/javascript">
  // Cambiar estado
  document.querySelectorAll('.btn-cambiar-estado').forEach(button => {
    button.addEventListener('click', function () {
      const form = this.closest('.form-cambiar-estado');
      const formData = new FormData(form);
      const action = form.getAttribute('action');

      fetch(action, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Estado cambiado exitosamente');
          location.reload(); // Recargar para ver el nuevo estado
        } else {
          alert('Error al cambiar el estado: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error. Inténtalo nuevamente.');
      });
    });
  });

  // Cancelar compra
  document.querySelectorAll('.btn-cancelar-compra').forEach(button => {
    button.addEventListener('click', function () {
      const form = this.closest('.form-cancelar-compra');
      const formData = new FormData(form);
      const action = form.getAttribute('action');

      fetch(action, {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert('Compra cancelada exitosamente');
          location.reload(); // Recargar para reflejar los cambios
        } else {
          alert('Error al cancelar la compra: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Ocurrió un error. Inténtalo nuevamente.');
      });
    });
  });
</script>

<?php include_once "../Estructura/footer.php"; ?>