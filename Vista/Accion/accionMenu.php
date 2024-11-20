<?php

include_once "../../configuracion.php";
$data = data_submitted();
$respuesta = false;
$mensaje = "";
$abmMenu = new AbmMenu();
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
            $list = $abmMenu->buscar($data);
            $arreglo_salida = array();
            foreach ($list as $elem) {
                $nuevoElem['idmenu'] = $elem->getIdMenu();
                $nuevoElem["menombre"] = $elem->getMenombre();
                $nuevoElem["medescripcion"] = $elem->getMedescripcion();
                $nuevoElem["idpadre"] = $elem->getObjMenuPadre();
                if ($elem->getObjMenuPadre() != null) {
                    $nuevoElem["idpadre"] = $elem->getObjMenuPadre()->getMeNombre();
                }
                $nuevoElem["medeshabilitado"] = $elem->getMedeshabilitado();
                array_push($arreglo_salida, $nuevoElem);
            }
            echo json_encode($arreglo_salida, null, 2);
            exit; // Salir para no ejecutar el resto del script
            break;

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