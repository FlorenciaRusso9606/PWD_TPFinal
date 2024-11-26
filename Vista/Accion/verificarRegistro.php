<?php
header('Content-Type: application/json');
include_once "../../configuracion.php";
include_once "../../Control/ControlPaginaAccion.php";
$session = new Session();
$datos = data_submitted();
$objValidar = new verificarSignup();
/* $passhash = md5($datos['uspass']); */

$resp = $objValidar->verificarSignup($datos);
echo json_encode([
	'success' => $resp,
	'message' => $resp ? 'Registro exitoso.' : 'Ya existe el mail o NO ingreso los datos correctamente'
]);
