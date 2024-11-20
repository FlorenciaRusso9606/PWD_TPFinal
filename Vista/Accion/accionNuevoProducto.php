<?php
include_once "../../configuracion.php";

$controlProducto = new ControlProducto();
$data =data_submitted();

// Realizar la acción según los datos
$resultado = $controlProducto->realizarAccion($data);

header('Content-Type: application/json');
echo json_encode([
    'success' => $resultado,
    'message' => $resultado ? 'Operación exitosa.' : 'No se pudo cambiar el estado.'
]);
exit;