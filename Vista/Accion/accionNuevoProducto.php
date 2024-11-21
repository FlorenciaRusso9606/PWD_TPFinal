<?php
include_once "../../configuracion.php";

$controlProducto = new ControlProducto();
$data = data_submitted();

// Realizar la acción según los datos
$resultado = $controlProducto->realizarAccion($data);

$response = array();
if ($resultado) {
    $response['success'] = true;
    $response['message'] = 'Producto creado exitosamente.';
} else {
    $response['success'] = false;
    $response['message'] = 'Hubo un error al crear el producto.';
}

header('Content-Type: application/json');
echo json_encode($response);
