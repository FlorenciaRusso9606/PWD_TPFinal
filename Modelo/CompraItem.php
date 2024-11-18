<?php
class CompraItem {
    private $idcompraitem;
    private $objProducto ;
    private $objCompra;
    private $cicantidad;
    private $mensajeoperacion;
    

    /**
     * @return mixed
     */
    public function getidcompraitem()
    {
        return $this->idcompraitem;
    }

    /**
     * @param mixed $idcompraitem
     */
    public function setidcompraitem($idcompraitem)
    {
        $this->idcompraitem = $idcompraitem;
    }

    /**
     * @return mixed
     */
    public function getobjProducto()
    {
        return $this->objProducto;
    }

    /**
     * @param mixed $objProducto
     */
    public function setobjProducto($objProducto)
    {
        $this->objProducto = $objProducto;
    }

    

    /**
     * @return mixed
     */
    public function getobjCompra()
    {
        return $this->objCompra;
    }

    /**
     * @param mixed $objCompra
     */
    public function setobjCompra($objCompra)
    {
        $this->objCompra = $objCompra;
    }

     /**
     * @return mixed
     */
    public function getcicantidad()
    {
        return $this->cicantidad;
    }

    /**
     * @param mixed $cicantidad
     */
    public function setcicantidad($cicantidad)
    {
        $this->cicantidad = $cicantidad;
    }


   


    /**
     * @return string
     */
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    /**
     * @param string $mensajeoperacion
     */
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function __construct(){
         $this->idcompraitem="";
         $this->objProducto=null ;
         $this->objCompra= null;
         $this->cicantidad="" ;
         $this->mensajeoperacion ="";
        
     }

     public function setear($idcompraitem, $objProducto,$objCompra, $cicantidad)    {
        $this->setidcompraitem($idcompraitem);
        $this->setobjProducto($objProducto);
        $this->setobjCompra($objCompra);
        $this->setcicantidad($cicantidad);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM compraitem WHERE idcompraitem = ".$this->getidcompraitem();
      //  echo $sql;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    $objCompra =null;
                    $objProducto=null;
                    if ($row['idcompra']!=null or $row['idcompra']!='' ){
                        $objCompra = new Compra();
                        $objCompra->setidcompra($row['idcompra']);
                        $objCompra->cargar();
                    }
                    if ($row['idproducto']!=null or $row['idproducto']!='' ){
                        $objProducto = new Producto();
                        $objProducto->setIdProducto($row['idproducto']);
                        $objProducto->cargar();
                    }
                    $this->setear($row['idcompraitem'],$objProducto,$row['medescripcion'],$objCompra); 
                    
                }
            }
        } else {
            $this->setmensajeoperacion("compraitem->cargar: ".$base->getError()[2]);
        }
        return $resp;
        
        
    }
    
    public function insertar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO compraitem (idproducto, idcompra, cicantidad) ";
        $sql .= "VALUES (" . $this->getobjProducto()->getIdProducto() . ", "
                         . $this->getobjCompra()->getidcompra() . ", "
                         . $this->getcicantidad() . ")";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setidcompraitem($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("compraitem->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("compraitem->insertar: " . $base->getError());
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE compraitem SET idproducto='".$this->getobjProducto()->getProductoId().",idcompra= ".$this->getobjCompra()->getidcompra().", cicantidad=".$this->getcicantidad()." WHERE idcompraitem = ".$this->getidcompraitem();
        // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setmensajeoperacion("compraitem->modificar 1: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("compraitem->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM compraitem WHERE idcompraitem =".$this->getidcompraitem();
       // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compraitem->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("compraitem->eliminar: ".$base->getError());
        }
        return $resp;
    }
    
    public static  function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM compraitem ";
     //   echo $sql;
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new CompraEstado();
                    $objCompra =null;
                    if ($row['idcompra']!=null){
                        $objCompra = new Compra();
                        $objCompra->setidcompra($row['idcompra']);
                        $objCompra->cargar();
                    }
                    if ($row['idproducto']!=null){
                        $objProducto = new Producto();
                        $objProducto->setIdProducto($row['idproducto']);
                        $objProducto->cargar();
                    }
                    $obj->setear($row['idcompraitem'], $$objProducto,$row['medescripcion'],$objCompra,$row['medeshabilitado']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    }
    }
?>