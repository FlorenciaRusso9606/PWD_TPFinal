<?php
class Menu
{
    private $idmenu;
    private $menombre;
    private $medescripcion;
    private $ObjMenu;
    private $medeshabilitado;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idmenu = "";
        $this->menombre = "";
        $this->medescripcion = "";
        $this->ObjMenu;
        $this->medeshabilitado;
        $this->mensajeoperacion = "";
    }

    public function setear($idmenu, $menombre, $medescripcion, $ObjMenu, $medeshabilitado)
    {
        $this->setIdmenu($idmenu);
        $this->setMenombre($menombre);
        $this->setMedescripcion($medescripcion);
        $this->setObjMenuPadre($ObjMenu);
        $this->setMedeshabilitado($medeshabilitado);
    }

    public function getIdmenu()
    {
        return $this->idmenu;
    }

    public function setIdmenu($idmenu)
    {
        $this->idmenu = $idmenu;
    }

    public function getMenombre()
    {
        return $this->menombre;
    }

    public function setMenombre($menombre)
    {
        $this->menombre = $menombre;
    }

    public function getMedescripcion()
    {
        return $this->medescripcion;
    }

    public function setMedescripcion($medescripcion)
    {
        $this->medescripcion = $medescripcion;
    }

    public function getObjMenuPadre()
    {
        return $this->ObjMenu;
    }

    public function setObjMenuPadre($ObjMenu)
    {
        $this->ObjMenu = $ObjMenu;
    }

    public function getMedeshabilitado()
    {
        return $this->medeshabilitado;
    }

    public function setMedeshabilitado($medeshabilitado)
    {
        $this->medeshabilitado = $medeshabilitado;
    }

    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM menu WHERE idmenu = " . $this->getIdmenu();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $objMenuPadre = null;
                    if ($row['idpadre'] != null or $row['idpadre'] != '') {
                        $objMenuPadre = new Menu();
                        $objMenuPadre->setIdmenu($row['idpadre']);
                        $objMenuPadre->cargar();
                    }
                    $this->setear($row['idmenu'], $row['menombre'], $row['medescripcion'], $objMenuPadre, $row['medeshabilitado']);
                }
            }
        } else {
            $this->setMensajeoperacion("Menu->cargar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO menu(menombre, medescripcion, idpadre, medeshabilitado) VALUES ('" . $this->getMenombre() . "', '" . $this->getMedescripcion() . "', ";
        if ($this->getObjMenuPadre() != null) {
            $sql .= $this->getObjMenuPadre()->getIdmenu() . ", ";
        } else {
            $sql .= "null, ";
        }
        if ($this->getMedeshabilitado() != null) {
            $sql .= "'" . $this->getMedeshabilitado() . "'";
        } else {
            $sql .= "null";
        }
        $sql .= ");";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setIdmenu($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("Menu->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("Menu->insertar: " . $base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE menu SET menombre='" . $this->getMenombre() . "', medescripcion='" . $this->getMedescripcion() . "'";
        if ($this->getObjMenuPadre() != null) {
            $sql .= ", idpadre=" . $this->getObjMenuPadre()->getIdmenu();
        } else {
            $sql .= ", idpadre=null";
        }
        if ($this->getMedeshabilitado() != null) {
            $sql .= ", medeshabilitado='" . $this->getMedeshabilitado() . "'";
        } else {
            $sql .= ", medeshabilitado=null";
        }
        $sql .= " WHERE idmenu=" . $this->getIdmenu();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Menu->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("Menu->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM menu WHERE idmenu=" . $this->getIdmenu();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("Menu->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("Menu->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM menu";
        if ($parametro != "") {
            $sql .= " WHERE " . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new Menu();
                    $objMenuPadre = null;
                    if ($row['idpadre'] != null) {
                        $objMenuPadre = new Menu();
                        $objMenuPadre->setIdmenu($row['idpadre']);
                        $objMenuPadre->cargar();
                    }
                    $obj->setear($row['idmenu'], $row['menombre'], $row['medescripcion'], $objMenuPadre, $row['medeshabilitado']);
                    array_push($arreglo, $obj);
                }
            }
        }
        return $arreglo;
    }
}
