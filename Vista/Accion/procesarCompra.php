<?php
include_once "../../configuracion.php";
$data = data_submitted();
$response = array('respuesta' => false);

if (isset($data['id'])) {
    $controlCompra = new ControlCompra();
    if ($controlCompra->confirmarCompra()) {
        $response['respuesta'] = true;
        $response['mensaje'] = 'Compra confirmada';
    } else {
        $response['mensaje'] = 'Error al confirmar la compra';
    }
} else {
    $response['mensaje'] = 'Datos incompletos';
}

echo json_encode($response);
