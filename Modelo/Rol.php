<?php

class Rol {
    private $idrol;
    private $roldescripcion;
    private $mensajeoperacion;

    public function __construct(){
        $this->idrol = "";
        $this->roldescripcion = "";
    }

    public function getidrol(){
        return $this->idrol;
    }

    public function setidrol($idrol){
        $this->idrol = $idrol;
    }

    public function getroldescripcion(){
        return $this->roldescripcion;
    }

    public function setroldescripcion($roldescripcion){
        $this->roldescripcion = $roldescripcion;
    }
   


    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }

    public function setMensajeOperacion($valor){
        $this->mensajeoperacion = $valor;
    }

    public function setear($idrol, $roldescripcion){
        $this->setidrol($idrol);
        $this->setroldescripcion($roldescripcion);
    }

    public function cargar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM rol WHERE idrol = '".$this->getidrol()."'";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res > -1){
                if($res > 0){
                    $row = $base->Registro();
                    $this->setear($row['idrol'], $row['roldescripcion']);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("compraestadotipo->cargar: ".$base->getError());
        }
        return $resp;
    }

    public function insertar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO rol (roldescripcion) VALUES ('" . $this->getroldescripcion() . "')";
        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $idrol = $base->lastInsertId();
                    if ($idrol) {
                        $this->setidrol($idrol);
                        $resp = true;
                    }
                } else {
                    $this->setMensajeOperacion("rol->insertar: " . $base->getError());
                }
            } else {
                $this->setMensajeOperacion("rol->insertar: " . $base->getError());
            }
        } catch (PDOException $e) {
            $this->setMensajeOperacion("rol->insertar: " . $e->getMessage());
        }
        return $resp;
    }

    public function modificar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE rol SET roldescripcion='".$this->getroldescripcion()."' WHERE idrol='".$this->getidrol()."'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("rol->modificar: ".$base->getError());
            }
        } else {
            $this->setMensajeOperacion("rol->modificar: ".$base->getError());
        }
        return $resp;
    }

    public function eliminar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM rol WHERE idrol='".$this->getidrol()."'";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("rol->eliminar: ".$base->getError());
            }
        } else {
            $this->setMensajeOperacion("rol->eliminar: ".$base->getError());
        }
        return $resp;
    }

    public static function listar($parametro=""){
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM rol";
        if ($parametro != "") {
            $sql .= ' WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res > -1){
            if($res > 0){
                while ($row = $base->Registro()){
                    $obj = new Rol();
                    $obj->setear($row['idrol'], $row['roldescripcion']);
                    array_push($arreglo, $obj);
                }
            }
        } else {
            error_log("rol->listar: ".$base->getError());
        }
        return $arreglo;
    }
}
?>
