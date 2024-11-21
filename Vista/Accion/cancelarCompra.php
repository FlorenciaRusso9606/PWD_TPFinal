<?php
include_once "../../configuracion.php";
include_once "../../Control/pagPublica.php";
$data = data_submitted();
$response = ["success" => false, "message" => "Ocurrió un error al procesar la solicitud."];

if (isset($data['idcompracancelar'])) {
    $controlCompra = new ControlCompra();
    if ($controlCompra->cancelarCompra($data)) {
        $response["success"] = true;
        $response["message"] = "Compra cancelada exitosamente.";
    } else {
        $response["message"] = "No se pudo cancelar la compra.";
    }
} else {
    $response["message"] = "Faltan datos para cancelar la compra.";
}

// Enviar la respuesta en formato JSON
header("Content-Type: application/json");
echo json_encode($response);
