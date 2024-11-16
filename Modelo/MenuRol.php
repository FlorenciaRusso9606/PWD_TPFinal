<?php

class MenuRol {
    private $objMenu;  // Este será un objeto de la clase objMenu
    private $objRol;      // Este será un objeto de la clase objRol
    private $mensajeoperacion;

    public function __construct() {
        $this->objMenu = new Menu();
        $this->objRol =  new Rol();
    }

    // Getters y Setters
    public function getobjMenu() {
        return $this->objMenu;
    }
    public function setobjMenu($objMenu) {
        $this->objMenu = $objMenu;
    }
    public function getobjRol() {
        return $this->objRol;
    }
    public function setobjRol($objRol) {
        $this->objRol = $objRol;
    }
    public function getMensajeOperacion() {
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($valor) {
        $this->mensajeoperacion = $valor;
    }

    // Setear objetos objMenu y objRol
    public function setear($objMenu, $objRol) {
        $this->setobjMenu($objMenu);
        $this->setobjRol($objRol);
    }
    public function setearConClave($idmenu, $idrol) {
        $this->getobjrol()->setIdRol($idrol);
        $this->getobjMenu()->setIdmenu($idmenu);
    }

    // Cargar desde la base de datos
    public function cargar() {
        $resp = false;
        $base = new BaseDatos();
        $idmenu = $this->getobjMenu()->getIdmenu();
        $idrol = $this->getobjRol()->getIdRol();

        if ($idmenu && $idrol) {
            $sql = "SELECT * FROM menurol WHERE idmenu = " . $idmenu . " AND idrol = " . $idrol;
            if ($base->Iniciar()) {
                $res = $base->Ejecutar($sql);
                if ($res > -1) {
                    if ($res > 0) {
                        $row = $base->Registro();
                        // Cargar el objeto objMenu y el objeto objRol
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

    // Insertar en la base de datos
    public function insertar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO menurol (idmenu, idrol) VALUES ('" . $this->getobjMenu()->getIdmenu() . "', '" . $this->getobjRol()->getIdRol() . "')";
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

    // Modificar
    public function modificar() {
        $resp = false;
        return $resp;
    }

    // Eliminar
    public function eliminar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM menurol WHERE idmenu=" . $this->getobjMenu()->getIdmenu() . " AND idrol=" . $this->getobjRol()->getIdRol();
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

    // Listar registros
    public static function listar($parametro = "") {
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
                    $obj->getobjMenu()->setIdmenu($row['idmenu']);
                    $obj->getobjrol()->setIdRol($row['idrol']);
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
