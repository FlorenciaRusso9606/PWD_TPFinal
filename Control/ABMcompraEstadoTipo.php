<?php

class ABMcompraEstadoTipo{

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
     * @return Tabla
     */
    private function cargarObjeto($param){
        $obj = null;
           
        if( array_key_exists('idcompraestadotipo',$param) and array_key_exists('cetdescripcion',$param) and array_key_exists('cetdetalle', $param)){
            $obj = new CompraEstadoTipo();
            $obj->cargar($param['idcompraestadotipo'], $param['cetdescripcion'], $param['cetdetalle']);
        }
        return $obj;
    }

    private function cargarObjetoConClave($id){
        $obj = null;

        if(isset($id['idcompraestadotipo'])){
            $obj = new CompraEstadoTipo();
            $obj->cargar($id['idcompraestadotipo'], null, null);
        }
        return $obj;   
    }

     /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $id
     * @return boolean
     */
    
     private function seteadosCamposClaves($id){
        $resp = false;
        if (isset($id['idcompraestado']))
            $resp = true;
        return $resp;
    }

    /**
     * 
     * @param array $param
     */
    public function alta($param){
        $resp = false;
        $param['idcompraestadotipo'] = null;
        $elObjtTabla = $this->cargarObjeto($param);
        if ($elObjtTabla != null and $elObjtTabla->insertar()){
            $resp = true;
        }
        return $resp;
    }

    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    public function baja($id){
        $resp = false;
        if ($this->seteadosCamposClaves($id)){
            $elObjtTabla = $this->cargarObjetoConClave($id);
            if ($elObjtTabla!=null and $elObjtTabla->eliminar()){
                $resp = true;
            }
        }
        
        return $resp;
    }

    /**
     * permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($id){
        //echo "Estoy en modificacion";
        $resp = false;
        if ($this->seteadosCamposClaves($id)){
            $elObjtTabla = $this->cargarObjeto($id);
            if($elObjtTabla!=null and $elObjtTabla->modificar()){
                $resp = true;
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
            if  (isset($param['idcompraestado']))
                $where.=" and idecompraestado =".$param['idcompraestado'];
            if  (isset($param['cetdescripcion']))
                 $where.=" and cetdescripcion ='".$param['cetdescripcion']."'";
            if (isset($param['cetdetalle']))
                $where.="and cetdetalle = '" .$param['cetdetalle']."'";
        }   
        $obj = new CompraEstadoTipo();
        $arreglo = $obj->listar($where);
        return $arreglo;
        
    }

}