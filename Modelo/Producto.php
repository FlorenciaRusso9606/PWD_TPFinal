<?php

class Producto {
    private $idproducto;
    private $pronombre;
    private $prodetalle;
    private $procantstock;
    private $proprecio;
    private $mensajeoperacion;

    public function __construct() {
        $this->idproducto = "";
        $this->pronombre = "";
        $this->prodetalle = "";
        $this->procantstock = "";
        $this->proprecio = "";
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

    public function getProPrecio() {
        return $this->proprecio;
    }
    public function setProPrecio($proprecio) {
        $this->proprecio = $proprecio;
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

    public function setear($idproducto, $pronombre, $prodetalle, $procantstock, $proprecio) {
        $this->setIdProducto($idproducto);
        $this->setProNombre($pronombre);
        $this->setProDetalle($prodetalle);
        $this->setProCantStock($procantstock);
        $this->setProPrecio($proprecio);
    }

    public function cargar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM producto WHERE idproducto = '" . $this->getIdProducto() . "'";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                $row = $base->Registro();
                $this->setear($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock'], $row['proprecio']);
                $resp = true;
            } else {
                $this->setMensajeOperacion("Producto no encontrado.");
            }
        } else {
            $this->setMensajeOperacion("producto->cargar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO producto (pronombre, prodetalle, procantstock, proprecio) VALUES ('" . $this->getProNombre() . "', '" . $this->getProDetalle() . "', " . $this->getProCantStock() . ", " . $this->getProPrecio() . ")";
        try {
            if ($base->Iniciar() && $base->Ejecutar($sql)) {
                $this->setIdProducto($base->lastInsertId());
                $resp = true;
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
        $sql = "UPDATE producto SET pronombre='" . $this->getProNombre() . "', prodetalle='" . $this->getProDetalle() . "', procantstock=" . $this->getProCantStock() . ", proprecio=" . $this->getProPrecio() . " WHERE idproducto=" . $this->getIdProducto();
        if ($base->Iniciar() && $base->Ejecutar($sql)) {
            $resp = true;
        } else {
            $this->setMensajeOperacion("producto->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM producto WHERE idproducto='" . $this->getIdProducto() . "'";
        if ($base->Iniciar() && $base->Ejecutar($sql)) {
            $resp = true;
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
            while ($row = $base->Registro()) {
                $obj = new Producto();
                $obj->setear($row['idproducto'], $row['pronombre'], $row['prodetalle'], $row['procantstock'], $row['proprecio']);
                array_push($arreglo, $obj);
            }
        } else {
            error_log("producto->listar: " . $base->getError());
        }
        return $arreglo;
    
    }}