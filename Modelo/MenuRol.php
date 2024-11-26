<?php

class MenuRol
{
    private $objMenu;
    private $objRol;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->objMenu = new Menu();
        $this->objRol = new Rol();
    }

    public function getObjMenu()
    {
        return $this->objMenu;
    }

    public function setObjMenu($objMenu)
    {
        $this->objMenu = $objMenu;
    }

    public function getObjRol()
    {
        return $this->objRol;
    }

    public function setObjRol($objRol)
    {
        $this->objRol = $objRol;
    }

    public function getMensajeOperacion()
    {
        return $this->mensajeoperacion;
    }

    public function setMensajeOperacion($valor)
    {
        $this->mensajeoperacion = $valor;
    }

    public function setear($objMenu, $objRol)
    {
        $this->setObjMenu($objMenu);
        $this->setObjRol($objRol);
    }

    public function setearConClave($idmenu, $idrol)
    {
        $this->getObjRol()->setIdRol($idrol);
        $this->getObjMenu()->setIdmenu($idmenu);
    }

    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $idmenu = $this->getObjMenu()->getIdmenu();
        $idrol = $this->getObjRol()->getIdRol();

        if ($idmenu && $idrol) {
            $sql = "SELECT * FROM menurol WHERE idmenu = " . $idmenu . " AND idrol = " . $idrol;
            if ($base->Iniciar()) {
                $res = $base->Ejecutar($sql);
                if ($res > -1) {
                    if ($res > 0) {
                        $row = $base->Registro();
                        $objMenu = new Menu();
                        $objMenu->setIdmenu($row['idmenu']);
                        $objMenu->cargar();
                        $objRol = new Rol();
                        $objRol->setIdRol($row['idrol']);
                        $objRol->cargar();
                        $this->setear($objMenu, $objRol);
                        $resp = true;
                    }
                }
            } else {
                $this->setMensajeOperacion("menurol->cargar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("menurol->cargar: idmenu o idrol no están establecidos.");
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO menurol (idmenu, idrol) VALUES ('" . $this->getObjMenu()->getIdmenu() . "', '" . $this->getObjRol()->getIdRol() . "')";

        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $resp = true;
                } else {
                    $this->setMensajeOperacion("menurol->insertar: " . $base->getError());
                }
            } else {
                $this->setMensajeOperacion("menurol->insertar: " . $base->getError());
            }
        } catch (PDOException $e) {
            $this->setMensajeOperacion("menurol->insertar: " . $e->getMessage());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM menurol WHERE idmenu=" . $this->getObjMenu()->getIdmenu() . " AND idrol=" . $this->getObjRol()->getIdRol();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("menurol->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("menurol->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminarPorMenu()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM menurol WHERE idmenu = " . $this->getObjMenu()->getIdmenu();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            }
        }
        return $resp;
    }

    public static function listar($parametro = "")
    {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM menurol";
        if ($parametro != "") {
            $sql .= ' WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new MenuRol();
                    $obj->getObjMenu()->setIdmenu($row['idmenu']);
                    $obj->getObjRol()->setIdRol($row['idrol']);
                    $obj->cargar();
                    array_push($arreglo, $obj);
                }
            }
        } else {
            error_log("menurol->listar: " . $base->getError());
        }
        return $arreglo;
    }
}