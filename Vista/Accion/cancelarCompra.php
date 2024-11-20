<?php
include_once "../../configuracion.php";
$response = ["success" => false, "message" => "Error desconocido"];
include_once "../configuracion.php";
$data = data_submitted();
if (isset($data['idcompracancelar'])) {
  $controlCompra = new ControlCompra;
  $compraCanceladaConExito = $controlCompra->cancelarCompra($data);
  if ($compraCanceladaConExito) {
    $response = ["success" => true, "message" => "Compra cancelada exitosamente"];
  } else {
    $response["message"] = "No se pudo cancelar la compra";
  }

  echo json_encode($response);
}
