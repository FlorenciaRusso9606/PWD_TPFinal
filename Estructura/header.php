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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>

    <!-- Hash MD5 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js"></script>

    <!-- Links Bootstrap MD5 -->
    <!-- <link rel="stylesheet" href="<?= $RUTAVISTA ?>Assets/bootstrap-5.3.3-dist/css/bootstrap.css">
    <link rel="stylesheet" href="<?= $RUTAVISTA ?>Assets/bootstrap-5.3.3-dist/js/bootstrap.js"> -->

    <!-- Link CSS -->
    <!-- <link rel="stylesheet" href="<?= $RUTAVISTA ?>Assets/css/style.css"> -->

    <link rel="icon" href="../Vista/Assets/img/favicon.webp">
    <title><?= $page_title ?></title>

    <style>
        body {
            display: grid;
            min-height: 100vh;
            grid-template-rows: auto 1fr auto;
            background-color: azure;
        }
    </style>
</head>

<body>
    <?php include $RUTANAV; ?>
    <main>