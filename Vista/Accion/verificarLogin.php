<?php
include_once "../../configuracion.php";
$data =data_submitted();

$login = new VerificarLogin;
$resp = $login->verificarLogin($data);
if($resp){
    header("Location :".$PRINCIPAL);
}else{
    header("Location :".$LOGIN."?error=1");
}