<?php

class Producto {
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private $mensajeoperacion;

    public function __construct() {
        $this->idproducto = "";
        $this->pronombre = "";
        $this->prodetalle = "";
        $this->procantstock = "";
        $this->mensajeoperacion = "";
    }

    public function getIdProducto() {
        return $this->idproducto;
    }
    public function setIdProducto($idproducto) {
        $this->idproducto = $idproducto;
    }

    public function getProNombre() {
        return $this->pronombre;
    }
    public function setProNombre($pronombre) {
        $this->pronombre = $pronombre;
    }

    public function getProDetalle() {
        return $this->prodetalle;
    }
    public function setProDetalle($prodetalle) {
        $this->prodetalle = $prodetalle;
    }

    public function getProCantStock() {
        return $this->procantstock;
    }
    public function setProCantStock($procantstock) {
        $this->procantstock = $procantstock;
    }

    public function getMensajeOperacion() {
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($valor) {
        $this->mensajeoperacion = $valor;
    }

    public function setear($idproducto, $pronombre, $prodetalle, $procantstock) {
        $this->setIdProducto($idproducto);
        $this->setProNombre($pronombre);
        $this->setProDetalle($prodetalle);
        $this->setProCantStock($procantstock);
    }

    public function cargar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM producto WHERE idproducto = '" . $this->getIdProducto() . "'";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $this->setear($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock']);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("producto->cargar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO producto (pronombre, prodetalle, procantstock) VALUES ('" . $this->getProNombre() . "', '" . $this->getProDetalle() . "', '" . $this->getProCantStock() . "')";
        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $idproducto = $base->lastInsertId();
                    if ($idproducto) {
                        $this->setIdProducto($idproducto);
                        $resp = true;
                    }
                } else {
                    $this->setMensajeOperacion("producto->insertar: " . $base->getError());
                }
            } else {
                $this->setMensajeOperacion("producto->insertar: " . $base->getError());
            }
        } catch (PDOException $e) {
            $this->setMensajeOperacion("producto->insertar: " . $e->getMessage());
        }
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE producto SET pronombre='" . $this->getProNombre() . "', prodetalle='" . $this->getProDetalle() . "', procantstock='" . $this->getProCantStock() . "' WHERE idproducto='" . $this->getIdProducto() . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("producto->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("producto->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM producto WHERE idproducto='" . $this->getIdProducto() . "'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("producto->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("producto->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($parametro = "") {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM producto";
        if ($parametro != "") {
            $sql .= ' WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new Producto();
                    $obj->setIdProducto($row['idproducto']);
                    $obj->cargar();
                    array_push($arreglo, $obj);
                }
            }
        } else {
            error_log("producto->listar: " . $base->getError());
        }
        return $arreglo;
    }
}
