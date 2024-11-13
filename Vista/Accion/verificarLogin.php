<?php
include_once "../../configuracion.php";
$data = data_submitted();
$abmUsuario = new AbmUsuario();
$usuario = $abmUsuario->buscar($data);


if (count($usuario) > 0) {
    //Usuario encontrado
    $session = new Session();
    $session->iniciar($usuario[0]->getUsuarioNombre(), $usuario[0]->getUsuarioPassword());
    header("Location: ../index.php");
} else {
    //Usuario no encontrado

    header("Location: ../login.php?error=true");
}
exit;
