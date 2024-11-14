<?php

include_once '../configuracion.php';

$objProducto = new AbmProducto();
$productos = $objProducto->buscar(NULL);

function mostrarProductos($arregloProd) {

    echo "<div class='ui special cards'>";
    foreach ($arregloProd as $producto) {


        echo "<div class='card'>";
        echo "<div class='content'>";
        echo "<div class='center'>";
        echo "</div>";
        echo "<div class='ui placeholder'>";
        echo "<div class='image'></div>";
        echo "</div>";
        echo "</div>";
        echo "<div class='content'>";
        echo "<a class='header'>" . $producto->getProNombre() . "</a>";
        echo "<div class='meta'>";
        echo "<span class='date'>" . $producto->getProDetalle() . "</span>";
        echo "</div>";
        echo "</div>";
        echo "<div class='ui vertical animated button' tabindex='0'>";
        echo "<div class='hidden content'>Shop</div>";
        echo "<div class='visible content'>";
        echo "<i class='shop icon'></i>";
        echo "</div></div>";
        echo "</div>";
    }
    echo "</div>";
}


?>
<!DOCTYPE html>
<?php include_once "../Estructura/header.php"; ?>

<div class="ui container">

    <div class="sixteen wide column">
        <div class="ui center aligned padded segment container">
            <?php
            echo "<h1>Productos</h1>";
            mostrarProductos($productos);

            ?>
        </div>
    </div>
</div>


<?php include_once "../Estructura/footer.php"; ?>