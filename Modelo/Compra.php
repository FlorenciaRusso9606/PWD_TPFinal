<?php
class Compra {
    private $idcompra;
    private $cofecha ;
    private $objUsuario;
    private $mensajeoperacion;
    

    /**
     * @return mixed
     */
    public function getidcompra()
    {
        return $this->idcompra;
    }

    /**
     * @param mixed $idcompra
     */
    public function setidcompra($idcompra)
    {
        $this->idcompra = $idcompra;
    }

    /**
     * @return mixed
     */
    public function getcofecha()
    {
        return $this->cofecha;
    }

    /**
     * @param mixed $cofecha
     */
    public function setcofecha($cofecha)
    {
        $this->cofecha = $cofecha;
    }

    

    /**
     * @return mixed
     */
    public function getobjUsuario()
    {
        return $this->objUsuario;
    }

    /**
     * @param mixed $objUsuario
     */
    public function setobjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
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
         $this->idcompra="";
         $this->cofecha="" ;
         $this->objUsuario= null;
         $this->mensajeoperacion ="";
        
     }

     public function setear($idcompra, $cofecha,$objUsuario)    {
        $this->setidcompra($idcompra);
        $this->setcofecha($cofecha);
        $this->setobjUsuario($objUsuario);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM Compra WHERE idcompra = ".$this->getidcompra();
      //  echo $sql;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    $objUsuario =null;
                    if ($row['idusuario']!=null or $row['idusuario']!='' ){
                        $objUsuario = new Usuario();
                        $objUsuario->setUsuarioId($row['idusuario']);
                        $objUsuario->cargar();
                    }
                    $this->setear($row['idcompra'], $row['cofecha'],$row['medescripcion'],$objUsuario,$row['medeshabilitado']); 
                    
                }
            }
        } else {
            $this->setmensajeoperacion("Compra->cargar: ".$base->getError()[2]);
        }
        return $resp;
        
        
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO Compra( cofecha  ,  idusuario)  ";
        $sql.="VALUES('".$this->getcofecha()."',".$this->getobjUsuario()->getUsuarioId().")";
     // echo $sql;
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setidcompra($elid);
                $resp = true;
            } else {
                $this->setmensajeoperacion("Compra->insertar: ".$base->getError()[2]);
            }
        } else {
            $this->setmensajeoperacion("Compra->insertar: ".$base->getError()[2]);
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE Compra SET cofecha='".$this->getcofecha().",idusuario= ".$this->getobjUsuario()->getUsuarioId()." WHERE idcompra = ".$this->getidcompra();
        // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setmensajeoperacion("Compra->modificar 1: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("Compra->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM Compra WHERE idcompra =".$this->getidcompra();
       // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Compra->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("Compra->eliminar: ".$base->getError());
        }
        return $resp;
    }
    
    public static  function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM Compra ";
     //   echo $sql;
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new Compra();
                    $objUsuario =null;
                    if ($row['idusuario']!=null){
                        $objUsuario = new Usuario();
                        $objUsuario->setUsuarioId($row['idusuario']);
                        $objUsuario->cargar();
                    }
                    $obj->setear($row['idcompra'], $row['cofecha'],$row['medescripcion'],$objUsuario,$row['medeshabilitado']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    }
    }
?>