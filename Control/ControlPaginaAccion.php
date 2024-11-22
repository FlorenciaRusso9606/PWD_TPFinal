<?php
include_once __DIR__ . "/../configuracion.php";

// Verificar si la solicitud es AJAX
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';

// Si no es una solicitud AJAX, redirigir al índice
if (!$isAjax) {
    header("Location: ../../index.php");
    exit();
}
