<?php

$nav = new ControlNav();
$menus = $nav->getSubMenus($rol);

?>

<?php if ($rol > 0) { ?>
    <nav class="ui container">
        <div class="sixteen wide column">
            <div class="ui menu">
                <a href="index.php" class="item">
                    Inicio
                </a>

                <?php foreach ($menus as $menuActual) { ?>
                    <a href="<?= $nav->getUrl($menuActual->getIdmenu()) ?>" class="item">
                        <?= $menuActual->getMedescripcion() ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </nav>
<?php } else { ?>
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
<?php } ?>