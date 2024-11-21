<?php
include_once "../../configuracion.php";
/* include_once "../../Control/pagPublica.php";   */
$data = data_submitted();
$login = new VerificarLogin();
$resp = $login->verificarLogin($data);

echo json_encode([
    'success' => $resp,
    'message' => $resp ? 'Login exitoso' : 'Usuario o contraseña incorrectos'
]);

