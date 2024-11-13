<?php 
include_once "../../../configuracion.php";
$data = data_submitted();
var_dump($data);
$respuesta = false;
if (isset($data['menombre'])){
        $objC = new AbmMenu();
        $respuesta = $objC->alta($data);
        $idMenu = $data["idmenu"];
        $idRol= null;
        $param = [
            "idmenu"=> $idMenu,
            "idRol" => $idRol
        ];
        
        $objMenuRol = new abmMenuRol();
        $respuesta2 = $objMenuRol->alta($param);
        if (!$respuesta){
            $mensaje = " La accion  ALTA No pudo concretarse";
            
        }
}
$retorno['respuesta'] = $respuesta;
if (isset($mensaje)){
    
    $retorno['errorMsg']=$mensaje;
   
}
 echo json_encode($retorno);
?>