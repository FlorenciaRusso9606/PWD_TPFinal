<?php
class AbmProducto {

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
           
        if( array_key_exists('idproducto',$param) and array_key_exists('pronombre',$param) and array_key_exists('prodetalle',$param) and array_key_exists('precio',$param) and array_key_exists('procantstock',$param)){
            $obj = new Producto();
            $obj->cargar($param['idproducto'], $param['pronombre'], $param['prodetalle'], $param['precio'], $param['procantstock']);
        }
        return $obj;
    }
    
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Tabla
     */
    private function cargarObjetoConClave($param){
        $obj = null;
        
        if( isset($param['idproducto']) ){
            $obj = new Producto();
            $obj->cargar($param['idproducto'], null, null, null, null);
        }
        return $obj;
    }
    
    
    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */
    
    private function seteadosCamposClaves($param){
        $resp = false;
        if (isset($param['idproducto']))
            $resp = true;
        return $resp;
    }
    
    /**
     * 
     * @param array $param
     */
    public function alta($param){
        $resp = false;
        $param['idproducto'] =null;
        $elObjtTabla = $this->cargarObjeto($param);
//        verEstructura($elObjtTabla);
        if ($elObjtTabla!=null and $elObjtTabla->insertar()){
            $resp = true;
        }
        return $resp;
        
    }
    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    public function baja($param){
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $elObjtTabla = $this->cargarObjetoConClave($param);
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
    public function modificacion($param){
        //echo "Estoy en modificacion";
        $resp = false;
        if ($this->seteadosCamposClaves($param)){
            $elObjtTabla = $this->cargarObjeto($param);
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
            if  (isset($param['idproducto']))
                $where.=" and idproducto =".$param['idproducto'];
            if  (isset($param['pronombre']))
                 $where.=" and pronombre ='".$param['pronombre']."'";
            if  (isset($param['prodetalle']))
                 $where.=" and prodetalle ='".$param['prodetalle']."'";
            if  (isset($param['procantstock']))
                 $where.=" and procantstock ='".$param['procantstock']."'";
        }
        $obj = new Producto();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }



     /**
     * Verifica si la cantidad enviada es mayor o igual a la cantidad de stock del producto
     */
    public function verificarStock($data){
        $objProducto = new abmProducto();
        $producto = $objProducto->buscar($data);
        if(isset($producto[0])){
            $cantidad = $producto[0]->getProCantStock();
        }
        return $cantidad;
    }
  


    /**
     * Renderizar productos JSON
     */
    public function renderProductosJSON($id = null){
        $listaProductosJSON = array();
        $abmProducto = new AbmProducto();
        $productos = $abmProducto->buscar($id);
        foreach ($productos as $producto) {      
            $prodJSON = $producto->jsonSerialize();
            $prodJSON['acciones'] = renderBotonesAcciones($producto->getIdProducto());
            array_push($listaProductosJSON,$prodJSON);
           
        }
        return $listaProductosJSON;
    }





    
}
?>