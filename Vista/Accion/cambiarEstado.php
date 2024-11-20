<?php
include_once "../../configuracion.php";

$data = data_submitted();
$response = ["success" => false, "message" => "Error desconocido."];

if (isset($data['nuevoestado']) && isset($data['idcompra'])) {
    $controlCompra = new ControlCompra();
    if ($controlCompra->cambiarEstado($data)) {
        $response["success"] = true;
        $response["message"] = "Estado cambiado exitosamente.";
    } else {
        $response["message"] = "No se pudo cambiar el estado.";
    }
} else {
    $response["message"] = "Faltan datos para cambiar el estado.";
}

// Enviar la respuesta en formato JSON
header("Content-Type: application/json");
echo json_encode($response);
