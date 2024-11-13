<?php

include_once "../configuracion.php";

$session = new Session();
$idUsuario = $session->getUsuario();

$res = false;
if ($idUsuario) {
    $abmUsuario = new AbmUsuario();
    $usuario = $abmUsuario->buscar(['idusuario' => $idUsuario]);

    if (count($usuario) > 0) {
        $usuario = $usuario[0];
        $res = true;
    }
}

function mostrarPopup() {
    $abmProducto = new AbmProducto();
    $arregloProd = $abmProducto->buscar(NULL);
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
                mostrarPopup();
                ?>
                <a class="item">
                    <i class="cart icon"></i>
                    Checkout
                </a>
                <a href="infoUsuario.php" class="item">
                    <i class="user icon"></i>
                    Cuenta
                </a>
                <a href="cerrarSesion.php" class="item">
                    <i class="sign out alternate icon"></i>
                    Cerrar Sesion
                </a>
            </div>
        </div>
        <div class="sixteen wide column">
            <div class="ui center aligned padded segment container grid">
                <div class="ui four wide column">
                    <div class="image">
                        <img class="ui massive image" src="Assets/img/image.png" alt="Defualt-Profile-Picture">
                    </div>
                </div>
                <div class="ui ten wide column">
                    <?php

                    if ($res == true) {
                        echo "<h1>Información del Usuario</h1>";
                        echo "<p><strong>Nombre de usuario:</strong> " . $usuario->getUsuarioNombre() . "</p>";
                        echo "<p><strong>Email:</strong> " . $usuario->getUsuarioEmail() . "</p>";
                        echo "<p><strong>Contraseña:</strong> " . str_repeat('*', strlen($usuario->getUsuarioPassword())) . "</p>";
                    } else {
                        echo "<h1>No se encontró el usuario</h1>";
                    }
                    ?>
                </div>

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