<?php

include_once "../../configuracion.php";
include_once "../../Control/pagPublica.php";  
include_once "../../Control/ControlPaginaAccion.php";
$data = data_submitted();
$respuesta = false;
$mensaje = "";
$abmRol = new AbmRol();

if (isset($data['accion'])) {
    $accion = $data['accion'];

    switch ($accion) {
        case 'alta':
            if (isset($data['roldescripcion'])) {
                $respuesta = $abmRol->alta($data);
                if (!$respuesta) {
                    $mensaje = "No se pudo dar de alta el rol";
                }
            } else {
                $mensaje = "Datos incompletos para dar de alta el rol";
            }
            break;

        case 'mod':
            if (isset($data['idrol'])) {
                $respuesta = $abmRol->modificacion($data);
                if (!$respuesta) {
                    $mensaje = "La acción MODIFICACIÓN no pudo concretarse";
                }
            } else {
                $mensaje = "Datos incompletos para modificar el rol";
            }
            break;

        case 'baja':
            if (isset($data['idrol'])) {
                $respuesta = $abmRol->baja($data);
                if (!$respuesta) {
                    $mensaje = "La acción ELIMINACIÓN no pudo concretarse";
                }
            } else {
                $mensaje = "Datos incompletos para eliminar el rol";
            }
            break;

        case 'listar':
            $list = $abmRol->buscar($data);
            $arreglo_salida = array();
            foreach ($list as $elem) {
                $nuevoElem['idrol'] = $elem->getIdRol();
                $nuevoElem["roldescripcion"] = $elem->getRolDescripcion();
                array_push($arreglo_salida, $nuevoElem);
            }
            echo json_encode($arreglo_salida, null, 2);
            exit; // Salir para no ejecutar el resto del script

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