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