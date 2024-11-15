<?php
include_once "../configuracion.php";
$data = data_submitted();
$carrito = new Carrito;
print_r($data);
if(isset($data['idproducto'])){
    echo "entro idproducto";
    $carrito->agregarProducto($data);
}
?>
<!DOCTYPE html>
<html lang="en">
<?include_once "../Estructura/header.php";?>
<div>
    <?php 
   var_dump($carrito);

   print_r($carrito)
    ?>
    
</div>

