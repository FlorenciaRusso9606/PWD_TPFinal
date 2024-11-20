<?php
include_once "../../configuracion.php";
$data = data_submitted();
$respuesta = false;
if (isset($data['menombre'])) {
    $objC = new AbmMenu();
    $respuesta = $objC->alta($data);

    $result = $objC->buscar(['menombre' => $data['menombre'], 'medescripcion' => $data['medescripcion']]);
    if (is_array($result) && count($result) > 0) {
        $idMenu = $result[0]->getIdmenu();
        $idRol = null;
        $param = [
            "idmenu" => $idMenu,
            "idrol" => $idRol
        ];
        $objMenuRol = new abmMenuRol();
        $respuesta2 = $objMenuRol->alta($param);
    }
    if (!$respuesta) {
        $mensaje = " La accion  ALTA No pudo concretarse";
    }
}
$retorno['respuesta'] = $respuesta;
if (isset($mensaje)) {
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);


if (isset($data['menombre'])) {
    $control = new ControlTablaMenu($data);
} else {
    $mensaje = "No se pudo dar de alta el menu";
}
$retorno['respuesta'] = $control;
if (isset($mensaje)) {
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);
