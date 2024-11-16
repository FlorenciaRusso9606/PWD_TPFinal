<?php
$objProducto = new AbmProducto();
$productos = $objProducto->buscar(NULL);
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


<nav class="ui container">

    <?php
    if ($rol >= 1) {
    ?>

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
                <?php if ($rol == 2) {
                    echo '<a href="menu/menu_list.php" class="item">
        <i class="user secret icon"></i>
        Modulo Administracion
    </a>';
                } ?>
            </div>
        </div>

    <?php
    }
    ?>


</nav>