<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");

// Configuración de la aplicación
$PROYECTO = 'TPS/TpFinalRo/TpFinal';
$ROOT = $_SERVER['DOCUMENT_ROOT'] . "/$PROYECTO/";

$_SESSION['ROOT'] = $ROOT;

$page_title = "TP Final - Grupo 12 - PWD 2024";

include_once($ROOT . 'util/funciones.php');

// Definición de rutas
$LOGIN = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/login.php";
$PRINCIPAL = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/index.php";
$RUTAVISTA = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/";
$REGISTRARSE = "http://" . $_SERVER['HTTP_HOST'] . "/$PROYECTO/vista/registrarse.php";

$rutalogo = "./img/";

// Variables de entorno
$_SERVER['LOGIN'] = $LOGIN;
$_SERVER['PRINCIPAL'] = $PRINCIPAL;
$_SERVER['REGISTRARSE'] = $REGISTRARSE;
$_SERVER['RUTAVISTA'] = $RUTAVISTA;



