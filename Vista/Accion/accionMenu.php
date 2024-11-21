<?php

include_once "../../configuracion.php";
include_once "../../Control/pagPublica.php";  
$data = data_submitted();
$respuesta = false;
$mensaje = "";
$controlTablaMenu = new ControlTablaMenu();

if (isset($data['accion'])) {
    $accion = $data['accion'];

    switch ($accion) {
        case 'alta':
            if (isset($data['menombre'])) {
                $respuesta = $controlTablaMenu->altaMenu($data);
                if (!$respuesta) {
                    $mensaje = "No se pudo dar de alta el menú";
                }
            } else {
                $mensaje = "Datos incompletos para dar de alta el menú";
            }
            break;

        case 'mod':
            if (isset($data['idmenu'])) {
                $respuesta = $controlTablaMenu->modificarMenu($data);
                if (!$respuesta) {
                    $mensaje = "La acción MODIFICACIÓN no pudo concretarse";
                }
            } else {
                $mensaje = "Datos incompletos para modificar el menú";
            }
            break;

        case 'baja':
            if (isset($data['idmenu'])) {
                $respuesta = $controlTablaMenu->bajaMenu($data);
                if (!$respuesta) {
                    $mensaje = "La acción ELIMINACIÓN no pudo concretarse";
                }
            } else {
                $mensaje = "Datos incompletos para eliminar el menú";
            }
            break;

        case 'listar':
            $arreglo_salida = $controlTablaMenu->listarMenu($data);

            echo json_encode($arreglo_salida, null, 2);
            exit;

        default:
            $mensaje = "Acción no válida";
            break;
    }
} else {
    $mensaje = "No se especificó ninguna acción";
}

$retorno['respuesta'] = $respuesta;
if ($mensaje != "") {
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);
