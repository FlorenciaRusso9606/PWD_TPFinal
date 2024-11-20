<?php
include_once "../../configuracion.php";
$data = data_submitted();
$response = ["success" => false, "message" => "Error desconocido"];
if($data['nuevoestado'] && $data['idcompra']){
    $controlCompra = new ControlCompra;
    if($controlCompra->cambiarEstado($data)){
        $response = ["success" => true, "message" => "Estado de compra cambiado exitosamente"];
    } else {
      $response["message"] = "No se pudo cambiar el estado de compra";
    }
}