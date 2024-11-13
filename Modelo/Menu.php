<?php
class Menu {
    private $idmenu;
    private $menombre ;
    private $medescripcion;
    private $ObjMenu;
    private $medeshabilitado;
    private $mensajeoperacion;
    

    /**
     * @return mixed
     */
    public function getIdmenu()
    {
        return $this->idmenu;
    }

    /**
     * @param mixed $idmenu
     */
    public function setIdmenu($idmenu)
    {
        $this->idmenu = $idmenu;
    }

    /**
     * @return mixed
     */
    public function getMenombre()
    {
        return $this->menombre;
    }

    /**
     * @param mixed $menombre
     */
    public function setMenombre($menombre)
    {
        $this->menombre = $menombre;
    }

    /**
     * @return mixed
     */
    public function getMedescripcion()
    {
        return $this->medescripcion;
    }

    /**
     * @param mixed $medescripcion
     */
    public function setMedescripcion($medescripcion)
    {
        $this->medescripcion = $medescripcion;
    }

    /**
     * @return mixed
     */
    public function getObjMenu()
    {
        return $this->ObjMenu;
    }

    /**
     * @param mixed $ObjMenu
     */
    public function setObjMenu($ObjMenu)
    {
        $this->ObjMenu = $ObjMenu;
    }

    /**
     * @return mixed
     */
    public function getMedeshabilitado()
    {
        return $this->medeshabilitado;
    }

    /**
     * @param mixed $medeshabilitado
     */
    public function setMedeshabilitado($medeshabilitado)
    {
        $this->medeshabilitado = $medeshabilitado;
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
         $this->idmenu="";
         $this->menombre="" ;
         $this->medescripcion="";
         $this->ObjMenu= null;
         $this->medeshabilitado = null;
         $this->mensajeoperacion ="";
        
     }

     public function setear($idmenu, $menombre,$medescripcion,$ObjMenu,$medeshabilitado)    {
        $this->setIdmenu($idmenu);
        $this->setMenombre($menombre);
        $this->setMedescripcion($medescripcion);
        $this->setObjMenu($ObjMenu);
        $this->setMedeshabilitado($medeshabilitado);
    }
    
    
    public function cargar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="SELECT * FROM menu WHERE idmenu = ".$this->getIdmenu();
      //  echo $sql;
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res>-1){
                if($res>0){
                    $row = $base->Registro();
                    $objMenuPadre =null;
                    if ($row['idpadre']!=null or $row['idpadre']!='' ){
                        $objMenuPadre = new Menu();
                        $objMenuPadre->setIdmenu($row['idpadre']);
                        $objMenuPadre->cargar();
                    }
                    $this->setear($row['idmenu'], $row['menombre'],$row['medescripcion'],$objMenuPadre,$row['medeshabilitado']); 
                    
                }
            }
        } else {
            $this->setmensajeoperacion("Menu->cargar: ".$base->getError()[2]);
        }
        return $resp;
        
        
    }
    
    public function insertar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="INSERT INTO menu( menombre ,  medescripcion ,  idpadre ,  medeshabilitado)  ";
        $sql.="VALUES('".$this->getMenombre()."','".$this->getMedescripcion()."',";
        if ($this->getObjMenu()!= null)
            $sql.=$this->getObjMenu()->getIdmenu().",";
        else
            $sql.="null,";
        if ($this->getMedeshabilitado()!=null)
            $sql.= "'".$this->getMedeshabilitado()."'";
        else 
            $sql.="null";
        $sql.= ");";
     // echo $sql;
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdmenu($elid);
                $resp = true;
            } else {
                $this->setmensajeoperacion("Menu->insertar: ".$base->getError()[2]);
            }
        } else {
            $this->setmensajeoperacion("Menu->insertar: ".$base->getError()[2]);
        }
        return $resp;
    }
    
    public function modificar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="UPDATE menu SET menombre='".$this->getMenombre()."',medescripcion='".$this->getMedescripcion()."'";
        if ($this->getObjMenu()!= null)
            $sql.=",idpadre= ".$this->getObjMenu()->getIdmenu();
         else
            $sql.=",idpadre= null";
         if ($this->getMedeshabilitado()!=null)
             $sql.= ",medeshabilitado='".$this->getMedeshabilitado()."'";
         else
              $sql.=" ,medeshabilitado=null";
        $sql.= " WHERE idmenu = ".$this->getIdmenu();
        // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
                
            } else {
                $this->setmensajeoperacion("Menu->modificar 1: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("Menu->modificar 2: ".$base->getError());
        }
        return $resp;
    }
    
    public function eliminar(){
        $resp = false;
        $base=new BaseDatos();
        $sql="DELETE FROM menu WHERE idmenu =".$this->getIdmenu();
       // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("Menu->eliminar: ".$base->getError());
            }
        } else {
            $this->setmensajeoperacion("Menu->eliminar: ".$base->getError());
        }
        return $resp;
    }
    
    public static  function listar($parametro=""){
        $arreglo = array();
        $base=new BaseDatos();
        $sql="SELECT * FROM menu ";
     //   echo $sql;
        if ($parametro!="") {
            $sql.='WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res>-1){
            if($res>0){
                
                while ($row = $base->Registro()){
                    $obj = new Menu();
                    $objMenuPadre =null;
                    if ($row['idpadre']!=null){
                        $objMenuPadre = new Menu();
                        $objMenuPadre->setIdmenu($row['idpadre']);
                        $objMenuPadre->cargar();
                    }
                    $obj->setear($row['idmenu'], $row['menombre'],$row['medescripcion'],$objMenuPadre,$row['medeshabilitado']); 
                    array_push($arreglo, $obj);
                }
                
            }
            
        } 
        
        return $arreglo;
    }
    }
?>