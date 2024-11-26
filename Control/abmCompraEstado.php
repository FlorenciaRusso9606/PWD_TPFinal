<?php

class AbmCompraEstado {

    private $mensajeOperacion;

    public function abm($datos){
        $resp = false;
        if($datos['accion']=='editar'){
            if($this->modificacion($datos)){
                $resp = true;
            }
        }
        if($datos['accion']=='borrar'){
            if($this->baja($datos)){
                $resp =true;
            }
        }
        if($datos['accion']=='nuevo'){
            if($this->alta($datos)){
                $resp =true;
            }
            
        }
        return $resp;

    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Compraestado
     */
    private function cargarObjeto($param) {
        $obj = null;
        if (array_key_exists('idcompraestado', $param) && array_key_exists('idcompra', $param)
            && array_key_exists('idcompraestadotipo', $param) && array_key_exists('cefechaini', $param)
            && array_key_exists('cefechafin', $param)) {
            $objCompra = new Compra();
            $objCompra->setidcompra($param['idcompra']);
            $objCompra->cargar();

            $objCompraEstadoTipo = new CompraEstadoTipo();
            $objCompraEstadoTipo->setidcompraestadotipo($param['idcompraestadotipo']);
            $objCompraEstadoTipo->cargar();

            $obj = new CompraEstado();
            $obj->setear($param['idcompraestado'], $objCompra, $objCompraEstadoTipo, $param['cefechaini'], $param['cefechafin']);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de 
     * las variables instancias del objeto que son claves
     * @param array $param
     * @return Compraestado
     */
    private function cargarObjetoConClave($param) {
        $obj = null;
        if (isset($param['idcompraestado'])) {
            $obj = new CompraEstado();
            $obj->setidcompraestado($param['idcompraestado']);
            $obj->cargar();
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */

     private function seteadosCamposClaves($param) {
        $resp = false;
        if (isset($param['idcompraestado']))
            $resp = true;
        return $resp;
    }

    /**
     * Inserta un objeto
     * @param array $param
     */
    public function alta($param) {
        $resp = false;
        $param['idcompraestado'] = null;
        $obj = $this->cargarObjeto($param);
        if ($obj != null && $obj->insertar()) {
            $resp = true;
        } else {
            $this->mensajeOperacion = $obj->getMensajeoperacion();
        }
        return $resp;
    }

    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    public function baja($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjetoConClave($param);
            if ($obj != null && $obj->eliminar()) {
                $resp = true;
            } else {
                $this->mensajeOperacion = $obj->getMensajeoperacion();
            }
        }
        return $resp;
    }

    /**
     * permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjeto($param);
            if ($obj != null && $obj->modificar()) {
                $resp = true;
            } else {
                $this->mensajeOperacion = $obj->getMensajeoperacion();
            }
        }
        return $resp;
    }

    /**
     * permite buscar un objeto
     * @param array $param
     * @return array
     */
    public function buscar($param){
        $where = " true "; 
        if ($param<>NULL){
            $where .= '';
            if  (isset($param['idcompraestado']))
                $where.=" and idcompraestado ='".$param['idcompraestado']."'"; 
            if  (isset($param['idcompra']))
                    $where.=" and idcompra ='".$param['idcompra']."'";
            if  (isset($param['idcompraestadotipo']))
                    $where.=" and idcompraestadotipo ='".$param['idcompraestadotipo']."'";
            if  (isset($param['cefechaini']))
                    $where.=" and cefechaini ='".$param['cefechaini']."'";
            if  (isset($param['cefechafin']))
                    $where.=" and cefechafin ='".$param['cefechafin']."'";
        }
        $obj = new CompraEstado();
        $arreglo =  $obj->listar($where);   
        return $arreglo;
    }


    public function buscarUltimoEstadoCompra($idCompra) {
        $parametro = "idcompra = " . $idCompra . " AND cefechafin IS NULL";
        $arreglo = CompraEstado::listar($parametro);
        return count($arreglo) > 0 ? $arreglo[0] : null;
    }

 
    /**
     * Cambia el estado de una compra siempre que el estado que se quiera cambiar sea mayor al ultimo estado de la compra
     * @param int $idCompra
     * @param int $idEstado
     */
    public function cambiarEstadoDeCompra($idCompra,$idEstado){

        date_default_timezone_set('America/Argentina/Buenos_Aires');

        $resp = ["status"=>false,"msg"=>"No se pudo cambiar el estado de la compra"];
        //Busco el ultimo estado de la compra
        $ultimoEstado = $this->buscarUltimoEstadoCompra($idCompra);
        //Si el ultimo estado es distinto al que quiero cambiar y que sea mayor que el anterior (no puedo volver para atras una compra cancelada.)
        $id_estado_anterior = $ultimoEstado->getCompraEstadoTipo()->getIdCompraEstadoTipo();

        if(($ultimoEstado != null) && ($id_estado_anterior != $idEstado) && ($idEstado > $id_estado_anterior)){
            //Seteo la fecha de fin del ultimo estado
            $ultimoEstado->setCefechafin(date("Y-m-d H:i:s"));
            //Modifico el ultimo estado
            $ultimoEstado->modificar();
            //Creo un nuevo estado
            $nuevoEstado = new CompraEstado();

            $compra = new Compra();
            $compra->setIdCompra($idCompra);

            //Si el estado es cancelado devuelvo el stock
            if($idEstado == 4 && $id_estado_anterior == 2){
                $resp = $this->devolverStock($idCompra);
            }
           
            $estado = new CompraEstadoTipo();
            $estado->setIdCompraEstadoTipo($idEstado);

            $param=["idcompraestado"=>$idCompra, "compra"=>$compra, "idcompraestadotipo"=>$estado, "cefechaini"=>date("Y-m-d H:i:s"), "cefechafin"=>null];
            $nuevoEstado= $this->cargarObjeto($param);
            
            //Inserto el nuevo estado
            $nuevoEstado->insertar();
            $resp = ["status"=>true,"msg"=>"Se cambio el estado de la compra"];       
        }
        return $resp;
    }
   
    public function confirmarCompra($idCompra){
        $resp = ["status"=>false,"msg"=>"No se pudo confirmar la compra, no hay stock suficiente"];
        $compraItems = new CompraItem();
        $compraItems->listar("idcompra = ".$idCompra);
        foreach($compraItems as $item){
            $items[] = $compraItems->getidcompraitem();
        }
        $itemsConfirmados = true;
        foreach($items as $item){
            if($item->getProducto()->getProcantstock() < $item->getCicantidad()){
                $itemsConfirmados = false;
            }
        }
        if($itemsConfirmados){
            //Si hay stock suficiente confirmo la compra y resto el stock
            foreach($items as $item){
                $item->getProducto()->setProcantstock($item->getProducto()->getProcantstock() - $item->getCicantidad());
                $item->getProducto()->modificar();
            }
            $this->cambiarEstadoDeCompra($idCompra,2);
            $resp = ["status"=>true,"msg"=>"Se confirmo la compra"];
        }
        return $resp;
    }


    public function devolverStock($idCompra){
        //Devolver stock de los productos cancelados
        $compraItems = new CompraItem();
        $compraItems->listar("idcompra = ".$idCompra);
        foreach($compraItems as $item){
            $items[] = $compraItems->getidcompraitem();
        }
        foreach($items as $item){
            $item->getProducto()->setProcantstock($item->getProducto()->getProcantstock() + $item->getCicantidad());
            $item->getProducto()->modificar();
        }

        $resp = ["status"=>true,"msg"=>"Se devolvio el stock"];
        return $resp;

    }

    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

}