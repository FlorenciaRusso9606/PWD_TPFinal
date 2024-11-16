<?php
include_once "../configuracion.php";
$data = data_submitted();
$carritoObj = new Carrito();
var_dump($data);
if (isset($data['idproducto']) && isset($data['cantidad'])) {
    $carritoObj->agregarProducto($data);
    echo "Producto agregado correctamente al carrito.";
} else {
    echo "Faltan datos para agregar el producto.";
}
// Obtener el contenido del carrito
$carrito = $carritoObj->obtenerCarrito();
?>
<!DOCTYPE html>
<html lang="en">
<?include_once "../Estructura/header.php";?>
<div>
    <?php 
 
    ?>
    
</div>

