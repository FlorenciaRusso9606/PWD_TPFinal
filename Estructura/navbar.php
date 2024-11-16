<nav class="ui container">
    <?php
    if ($rol >= 1) {
    ?>
        <div class="sixteen wide column">
            <div class="ui menu">
                <a href="index.php" class="item">
                    Inicio
                </a>
                <a href="libros.php" class="item">
                    Libros
                </a>
                <a href="cuentos.php" class="item">
                    Cuentos
                </a>
                <a href="historietas.php" class="item">
                    Historietas
                </a>
                <a href="infoUsuario.php" class="item">
                    <i class="user icon"></i>
                    Mi Cuenta
                </a>
                <a href="cerrarSesion.php" class="item">
                    <i class="sign out alternate icon"></i>
                    Cerrar Sesión
                </a>
                <?php if ($rol == 1) {
                    echo '<a href="menu/menu_list.php" class="item">
        <i class="user secret icon"></i>
        Módulo Administración
    </a>';
                } ?>
            </div>
        </div>
    <?php
    } elseif ($rol == null) {
    ?>
        <div class="sixteen wide column">
            <div class="ui menu">
                <a href="index.php" class="item">
                    Inicio
                </a>
                <a href="libros.php" class="item">
                    Libros
                </a>
                <a href="cuentos.php" class="item">
                    Cuentos
                </a>
                <a href="historietas.php" class="item">
                    Historietas
                </a>
                <a href="../Vista/login.php" class="item">
                    <i class="user icon"></i>
                    Iniciar Sesión
                </a>
                <a href="cerrarSesion.php" class="item">
                    <i class="sign out alternate icon"></i>
                    registrarse
                </a>
            </div>
        </div>
    <?php
    }
    ?>
</nav>