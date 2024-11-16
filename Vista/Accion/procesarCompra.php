<?php
include_once "../../configuracion.php";

$data = data_submitted();

$compra = new ControlCompra;

var_dump($compra);

$resp = $compra->confirmarCompra();
if($resp == 5){
    $resp = ["respuesta" => true];

}elseif ($resp == 4){
    // header("Location: ".$PRINCIPAL."?error=$resp.");
    $resp = ["respuesta" => false];
}


json_encode($resp);