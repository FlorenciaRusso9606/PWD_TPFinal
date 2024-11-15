<?php
include_once "../../configuracion.php";

$session = new Session;
$idsuario = $session->getUsuario();

$data = data_submitted();
if($_SESSION['idCompra'] == null){
    $abmCompra= new abmCompra;
    $buscarCompra = $abmCompra->buscar($idusuario);
    $cofecha = time();
    $param = [ 
        'idusuario' => $idUsuario,
        'cofecha' => $cofecha,
        'accion' => 'nuevo'
    
    ];
    $rtaCreacionCompra = $abmCompra->abm($param);
    if($rtaCreacionCompra){
        $admCompra->buscar( ["idusuario"=>$idUsuario,"cofecha"=> $cofecha]);
    }
    
    $session->setCompra();
}



if(!empty($data['idproducto'])){
    $abmProducto = new AbmProducto;
    $listaProductos = $abmProducto->buscar($data);
    $producto= $listaProductos[0];

    
    if($producto->verificarStock($data)>0){
        $objCompraItem = new ABMCompraItem;
        $respuesta = $objCompraItem->abm($data);
        if($respuesta){
            
         header("Location: ".$PRINCIPAL);
        }else{
            header("Location: ".$PRINCIPAL."?error=1"); // no se cargó producto
        }
    }else{
        header("Location: ".$PRINCIPAL."?error=2"); // No hay stock
    }   
    
    
}