<?php

class CompraEstadoTipo {
    private $idcompraestadotipo;
    private $cetdescripcion;
    private $cetdetalle;
    private $mensajeoperacion;

    public function __construct(){
        $this->idcompraestadotipo = "";
        $this->cetdescripcion = "";
    }

    public function getidcompraestadotipo(){
        return $this->idcompraestadotipo;
    }

    public function setidcompraestadotipo($idcompraestadotipo){
        $this->idcompraestadotipo = $idcompraestadotipo;
    }

    public function getcetdescripcion(){
        return $this->cetdescripcion;
    }

    public function setcetdescripcion($cetdescripcion){
        $this->cetdescripcion = $cetdescripcion;
    }
    public function getcetdetalle(){
        return $this->cetdetalle;
    }

    public function setcetdetalle($cetdetalle){
        $this->cetdetalle = $cetdetalle;
    }
   
   


    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }

    public function setMensajeOperacion($valor){
        $this->mensajeoperacion = $valor;
    }

    public function setear($idcompraestadotipo, $cetdescripcion, $cetdetalle){
        $this->setidcompraestadotipo($idcompraestadotipo);
        $this->setcetdescripcion($cetdescripcion);
        $this->setcetdetalle($cetdetalle);
    }

    public function cargar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestadotipo WHERE idcompraestadotipo = " . $this->getidcompraestadotipo();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res > -1){
                if($res > 0){
                    $row = $base->Registro();
                    $this->setear($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeoperacion("compraestadotipo->cargar: ".$base->getError());
        }
        return $resp;
    }

    public function insertar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO compraestadotipo (cetdescripcion, cetdetalle) VALUES ('" . $this->getcetdescripcion() . "', '" . $this->getcetdetalle() . "')";
        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $idcompraestadotipo = $base->lastInsertId();
                    if ($idcompraestadotipo) {
                        $this->setidcompraestadotipo($idcompraestadotipo);
                        $resp = true;
                    }
                } else {
                    $this->setMensajeoperacion("compraestadotipo->insertar: " . $base->getError());
                }
            } else {
                $this->setMensajeoperacion("compraestadotipo->insertar: " . $base->getError());
            }
        } catch (PDOException $e) {
            $this->setMensajeoperacion("compraestadotipo->insertar: " . $e->getMessage());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE compraestadotipo SET cetdescripcion='" . $this->getcetdescripcion() . "', cetdetalle='" . $this->getcetdetalle() . "' WHERE idcompraestadotipo=" . $this->getidcompraestadotipo();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("compraestadotipo->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("compraestadotipo->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM compraestadotipo WHERE idcompraestadotipo=" . $this->getidcompraestadotipo();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("compraestadotipo->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("compraestadotipo->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($parametro=""){
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestadotipo";
        if ($parametro != "") {
            $sql .= ' WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res > -1){
            if($res > 0){
                while ($row = $base->Registro()){
                    $obj = new CompraEstadoTipo();
                    $obj->setear($row['idcompraestadotipo'], $row['cetdescripcion'], $row['cetdetalle']);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            error_log("compraestadotipo->listar: " . $base->getError());
        }
        return $arreglo;
    }
}
?>
