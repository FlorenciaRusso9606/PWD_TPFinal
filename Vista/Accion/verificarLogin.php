<?php
include_once "../../configuracion.php";
$data = data_submitted();
$login = new VerificarLogin();
$resp = $login->verificarLogin($data);

echo json_encode([
    'success' => $resp,
    'message' => $resp ? 'Login exitoso' : 'Usuario o contraseña incorrectos'
]);

