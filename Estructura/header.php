<?php

include_once '../configuracion.php';

$session = new Session();
$rol = $session->getRol();

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
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?= $RUTAVISTA ?>Assets/Semantic-UI-CSS-master/semantic.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= $RUTAVISTA ?>Assets/Semantic-UI-CSS-master/semantic.js"></script>
    <script src="<?= $RUTAVISTA ?>Assets/js/md5.js"></script>
    <link rel="stylesheet" href="<?= $RUTAVISTA ?>Assets/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $RUTAVISTA ?>Assets/bootstrap-5.3.3-dist/js/bootstrap.min.js">
    <link rel="stylesheet" href="<?= $RUTAVISTA ?>Assets/css/styles.css">

    <title><?= $page_title ?></title>


</head>

<body>
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
    <?php include $RUTANAV; ?>
    <main>