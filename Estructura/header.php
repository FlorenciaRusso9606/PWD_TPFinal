<?php

include_once '../configuracion.php';

$session = new Session();
$rol = $session->getRol();


?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Link Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Link Semantic UI -->
    <link rel="stylesheet" href="<?= $RUTAVISTA ?>Assets/Semantic-UI-CSS-master/semantic.css">
    <script src="<?= $RUTAVISTA ?>Assets/Semantic-UI-CSS-master/semantic.js"></script>
    

    <!-- Hash MD5 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script> 

    <!-- Links Bootstrap MD5 -->
    <link rel="stylesheet" href="<?= $RUTAVISTA ?>Assets/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $RUTAVISTA ?>Assets/bootstrap-5.3.3-dist/js/bootstrap.min.js">
    <!-- Link CSS -->
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