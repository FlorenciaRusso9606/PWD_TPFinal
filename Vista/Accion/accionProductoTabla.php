<?php

include_once "../../configuracion.php";
include_once "../../Control/ControlPaginaAccion.php";
$data = data_submitted();
$respuesta = false;
$mensaje = "";
$abmProducto = new AbmProducto();

if (isset($data['accion'])) {
    $accion = $data['accion'];

    switch ($accion) {
        case 'alta':
            if (isset($data['pronombre']) && isset($data['prodetalle']) && isset($data['procantstock']) && isset($data['proprecio'])) {
                $respuesta = $abmProducto->alta($data);
                if (!$respuesta) {
                    $mensaje = "No se pudo dar de alta el producto";
                }
            } else {
                $mensaje = "Datos incompletos para dar de alta el producto";
            }
            break;

        case 'mod':
            if (isset($data['idproducto']) && isset($data['pronombre']) && isset($data['prodetalle']) && isset($data['procantstock']) && isset($data['proprecio'])) {
                $respuesta = $abmProducto->modificacion($data);
                if (!$respuesta) {
                    $mensaje = "La acción MODIFICACIÓN no pudo concretarse";
                }
            } else {
                $mensaje = "Datos incompletos para modificar el producto";
            }
            break;

        case 'baja':
            if (isset($data['idproducto'])) {
                $respuesta = $abmProducto->baja($data);
                if (!$respuesta) {
                    $mensaje = "No se pudo eliminar el producto";
                }
            } else {
                $mensaje = "Datos incompletos para eliminar el producto";
            }
            break;

        case 'listar':
            $list = $abmProducto->buscar($data);
            $arreglo_salida = array();
            foreach ($list as $elem) {
                $nuevoElem['idproducto'] = $elem->getIdProducto();
                $nuevoElem["pronombre"] = $elem->getProNombre();
                $nuevoElem["prodetalle"] = $elem->getProDetalle();
                $nuevoElem["procantstock"] = $elem->getProCantStock();
                $nuevoElem["proprecio"] = $elem->getProPrecio();
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
