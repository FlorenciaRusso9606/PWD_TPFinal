<?php
include_once "../../configuracion.php";
include_once "../../Control/pagPublica.php";  
include_once "../../Control/ControlPaginaAccion.php";
$data = data_submitted();
$objControl = new AbmMenu();
$list = $objControl->buscar($data);
$arreglo_salida =  array();
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
//verEstructura($arreglo_salida);
echo json_encode($arreglo_salida, null, 2);
