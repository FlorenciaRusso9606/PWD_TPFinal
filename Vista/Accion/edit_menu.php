<?php
include_once "../../configuracion.php";
include_once "../../Control/pagPublica.php";  
$data = data_submitted();
$abmMenu = new AbmMenu();
$respuesta = $abmMenu->modificacion($data);
if (!$respuesta) {
    $mensaje = "La acción MODIFICACIÓN no pudo concretarse";
}
$retorno['respuesta'] = $respuesta;
if (isset($mensaje)) {
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);
