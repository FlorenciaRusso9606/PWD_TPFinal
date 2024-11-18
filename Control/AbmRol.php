<?php
class AbmRol {
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
    private function cargarObjeto($param){
        $obj = null;
        if( array_key_exists('idrol', $param) and array_key_exists('roldescriction', $param )){
            $obj = new Rol();
            $obj->setear($param['idrol'], $param['roldescriction']);
        }
        return $obj;
    }

    private function cargarObjetoConClave($param){
        $obj = null;
        if( isset($param['idrol']) ){
            $obj = new Rol();
            $obj->setIdRol($param['idrol'], null);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['idrol']))
            $resp = true;
        return $resp;
    }

    public function alta($param){
        $resp = false;        
        $param['idrol'] =null;
        $objUsuario = $this->cargarObjeto($param);
        if ($objUsuario != null and $objUsuario->insertar()){
            $resp = true;
        }
        return $resp;
    }

    public function baja($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $objUsuario = $this->cargarObjetoConClave($param);
            if ($objUsuario != null and $objUsuario->eliminar()){
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $objUsuario = $this->cargarObjeto($param);
            if($objUsuario != null and $objUsuario->modificar()){
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param){
        $where = " true ";
        if ($param != NULL){
            if (isset($param['idrol'])){
                $where .= " and idrol = '".$param['idrol']."'";
            }
        }
        $arreglo = Rol::listar($where);  
        return $arreglo;
    }
}
?>