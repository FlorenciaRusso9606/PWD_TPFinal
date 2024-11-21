<?php

include_once '../../configuracion.php';
include_once "../../Control/pagPublica.php";  
$datos = data_submitted();

$retorno = [];

if (isset($datos['idusuario'])) {
    $objModUser = new abmUsuario();
    $resp = $objModUser->modificacion($datos);

    if ($resp) {
        $retorno['respuesta'] = true;
        $retorno['usnombre'] = $datos['usnombre'];
        $retorno['usmail'] = $datos['usmail'];
        $retorno['uspassLength'] = strlen($datos['uspass']);
    } else {

        $retorno['respuesta'] = false;
        $retorno['errorMsg'] = 'La modificación no pudo concretarse.';
    }
} else {

    $retorno['respuesta'] = false;
    $retorno['errorMsg'] = 'ID de usuario no proporcionado.';
}

echo json_encode($retorno);
