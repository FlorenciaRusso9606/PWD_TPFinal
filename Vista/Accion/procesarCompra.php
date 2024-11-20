<?php
include_once "../../configuracion.php";
include_once "../../Control/pagPublica.php";  
$data = data_submitted();
$response = array('respuesta' => false);

if (isset($data['id'])) {
    $controlCompra = new ControlCompra();
    if ($controlCompra->confirmarCompra()) {
        $response['respuesta'] = true;
        $response['mensaje'] = 'Compra confirmada';
        /* $response['idcompra'] = */
    } else {
        $response['mensaje'] = 'Error al confirmar la compra';
    }
} else {
    $response['mensaje'] = 'Datos incompletos';
}

if (isset($data['limpiar'])) {
    $session = new Session();
    $session->setCarrito([]);
    $response['respuesta'] = true;
    $response['mensaje'] = 'Carrito limpiado';
}

echo json_encode($response);
