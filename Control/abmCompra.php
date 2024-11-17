<?php

class abmCompra {
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
     * @return Compra
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idcompra',$param)){
            $obj = new Compra();
            $obj->setidcompra($param['idcompra']);
            $obj->setcofecha($param['cofecha']);
            $obj->setobjUsuario($param['idusuario']);
            $obj->cargar();
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de 
     * las variables instancias del objeto que son claves
     * @param array $param
     * @return Compra
     */
    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idcompra'])) {
            $obj = new Compra();
            $obj->setidcompra($param['idcompra']);
            $obj->cargar();
        }
        return $obj;
    }

    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idcompra']))
            $resp = true;
        return $resp;
    }

     /**
     * Inserta un objeto
     * @param array $param
     */
    public function alta($param)
    {
        $resp = false;
        $param['idcompra'] = null;
        $obj = $this->cargarObjeto($param);
        if ($obj != null and $obj->insertar()) {
            $resp = true;
        }
        else {
            $this->mensajeOperacion = $obj->getMensajeoperacion();
        }
        return $resp;
    }

    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjetoConClave($param);
            if ($obj != null and $obj->eliminar()) {
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
    public function modificacion($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $obj= $this->cargarObjeto($param);
            if($obj!=null && $obj->modificar()){
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
            if  (isset($param['idcompra']))
                $where.=" and idcompra ='".$param['idcompra']."'"; 
            if  (isset($param['cofecha']))
                    $where.=" and cofecha ='".$param['cofecha']."'";
            if  (isset($param['idusuario']))
                    $where.=" and idusuario ='".$param['idusuario']."'";
        }
        $obj = new Compra();
        $arreglo =  $obj->listar($where);  
        return $arreglo;
    }



/**
     * Devuelve el mensaje de operación
     * @return string
     */
    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }

   


}


