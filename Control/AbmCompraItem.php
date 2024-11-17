<?php
class ABMCompraItem{
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
     * @return CompraItem
     */
    private function cargarObjeto($param) {
        $obj = null;
        if (array_key_exists('idcompraitem', $param) && array_key_exists('idproducto', $param)
            && array_key_exists('idcompra', $param) && array_key_exists('cicantidad', $param)) {
            $objProducto = new Producto();
            $objProducto->setIdProducto($param['idproducto']);
            $objProducto->cargar();

            $objCompra = new Compra();
            $objCompra->setIdCompra($param['idcompra']);
            $objCompra->cargar();

            $obj = new CompraItem();
            $obj->setear($param['idcompraitem'], $objProducto, $objCompra, $param['cicantidad']);
        }
        return $obj;
    }
    
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return CompraItem
     */
    
    private function cargarObjetoConClave($param){
        $obj = null;
        if( isset($param['idcompraitem']) ){
            $obj = new CompraItem();
            $obj->setidcompraitem($param['idcompraitem']);
            $obj->cargar();
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
        if (isset($param['idcompraitem']))
            $resp = true;
        return $resp;
    }
    
    public function alta($param){
        $resp = false;
        $param['idcompraitem'] =null;
        $elObjtTabla = $this->cargarObjeto($param);

        if ($elObjtTabla != null) {
            if ($elObjtTabla->insertar()) {
                $resp = true;
            } else {
                $this->mensajeOperacion = $elObjtTabla->getMensajeOperacion();
            }
        } else {
            $this->mensajeOperacion = "Error al cargar el objeto CompraItem.";
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
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjetoConClave($param);
            if ($elObjtTabla != null) {
                if ($elObjtTabla->modificar()) {
                    $resp = true;
                } else {
                    $this->mensajeOperacion = $elObjtTabla->getMensajeOperacion();
                }
            } else {
                $this->mensajeOperacion = "Error al cargar el objeto CompraItem.";
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
            } else {
                $this->mensajeOperacion = $elObjtTabla->getMensajeOperacion();
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
            if  (isset($param['idcompraitem']))
                $where.=" and idcompraitem =".$param['idcompraitem'];
            if  (isset($param['idproducto']))
                 $where.=" and idproducto ='".$param['idproducto']."'";
            if  (isset($param['idcompra']))
                 $where.=" and idcompra ='".$param['idcompra']."'";
            if  (isset($param['cicantidad']))
                 $where.=" and cicantidad ='".$param['cicantidad']."'";
        }
        $obj = new CompraItem();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }
    public function getMensajeOperacion() {
        return $this->mensajeOperacion;
    }
}
?>