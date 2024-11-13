<?php

include_once "../configuracion.php";

$session = new Session();
$session->cerrar();
header("Location: login.php");
exit;
