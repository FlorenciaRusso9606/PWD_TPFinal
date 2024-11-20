<?php
include_once "../configuracion.php";
$session = new Session();
$idUsuario = $session->getUsuario();
/* basename — Devuelve el último componente de nombre de una ruta */
$pagActual = basename($_SERVER['PHP_SELF']);
$pagPermitidas = ['index.php', 'login.php', 'productoPag.php'];
// Por si se ingresa a través de la URL
if ($idUsuario == null && !in_array($pagActual, $pagPermitidas)) {
	header("Location: " . $PRINCIPAL);
}
/* var_dump($PRINCIPAL); */
?>