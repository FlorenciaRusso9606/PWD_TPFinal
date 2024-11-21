<?php
include_once "../../configuracion.php";

$controlProducto = new ControlProducto();
$data = data_submitted();

// Realizar la acción según los datos
$resultado = $controlProducto->realizarAccion($data);

$response = array();
if ($resultado) {
    $response['success'] = true;
    $response['message'] = 'Accion realizada con exito.';
} else {
    $response['success'] = false;
    $response['message'] = 'Hubo un error.';
}

header('Content-Type: application/json');
echo json_encode($response);
