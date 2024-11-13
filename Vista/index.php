<?php
require_once "../configuracion.php";

$session = new Session();
if (!$session->validar()) {
    header("Location: login.php");
    exit;
}
$rol= $session->getRol();

var_dump($rol);

echo "<br><br>";
echo "<br><br>";

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

function mostrarPopup($arregloProd) {
    echo "<div class='ui fluid popup bottom left transition hidden'>";
    echo "<div class='ui four column relaxed equal height divided grid'>";
    foreach ($arregloProd as $producto) {
        echo "<div class='column'>";
        echo "<h4 class='ui header'>" . $producto->getProDetalle() . "</h4>";
        echo "<div class='ui link list'>";
        echo "<a class='item'>" . $producto->getProNombre() . "</a>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
    echo "</div>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="Assets/Semantic-UI-CSS-master/semantic.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="Assets/Semantic-UI-CSS-master/semantic.js"></script>
</head>

<body>

    <div class="ui  container">
        <div class="sixteen wide column">

            <div class="ui menu">
                <a class="browse item">
                    Browse
                    <i class="dropdown icon"></i>
                </a>
                <?php
                mostrarPopup($productos);
                ?>
                <a class="item">
                    <i class="cart icon"></i>
                    Mi Carrito
                </a>
                <a href="infoUsuario.php" class="item">
                    <i class="user icon"></i>
                    Cuenta
                </a>
                <a href="cerrarSesion.php" class="item">

                    <i class="sign out alternate icon"></i>
                    Cerrar Sesion
                </a>
                <?php if($rol == 2){
                    echo '<a href="menu/menu_list.php" class="item">
                    <i class="user secret icon"></i>
                    Modulo Administracion
                </a>';
                } ?>
            </div>
        </div>
        <div class="sixteen wide column">
            <div class="ui center aligned padded segment container">
                <?php
                echo "<h1>Productos</h1>";
                mostrarProductos($productos);

                ?>
            </div>
        </div>
    </div>

    <script>
        $('.menu .browse')
            .popup({
                inline: true,
                hoverable: true,
                position: 'bottom left',
                delay: {
                    show: 300,
                    hide: 800
                }
            });
    </script>


</body>

</html>