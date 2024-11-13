<?php

class UsuarioRol {
    private $objUsuario;  // Este será un objeto de la clase objUsuario
    private $objRol;      // Este será un objeto de la clase objRol
    private $mensajeoperacion;

    public function __construct(){
        $this->objUsuario = new Usuario();
        $this->objRol =  new Rol();
    }

    // Getters y Setters
    public function getobjUsuario(){
        return $this->objUsuario;
    }
    public function setobjUsuario($objUsuario){
        $this->objUsuario = $objUsuario;
    }
    public function getobjRol(){
        return $this->objRol;
    }
    public function setobjRol($objRol){
        $this->objRol = $objRol;
    }
    public function getMensajeOperacion(){
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($valor){
        $this->mensajeoperacion = $valor;
    }

    // Setear objetos objUsuario y objRol
    public function setear($objUsuario, $objRol){
        $this->setobjUsuario($objUsuario);
        $this->setobjRol($objRol);
    }
    public function setearConClave($idusuario, $idrol)
    {
        $this->getobjrol()->setIdRol($idrol);
        $this->getobjusuario()->setUsuarioId($idusuario);
    }

    // Cargar desde la base de datos
    public function cargar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM UsuarioRol WHERE idusuario = ".$this->getobjUsuario()->getUsuarioId()." AND idrol = ".$this->getobjRol()->getIdRol();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if($res > -1){
                if($res > 0){
                    $row = $base->Registro();
                    // Cargar el objeto objUsuario y el objeto objRol
                    $objUsuario = new Usuario();
                    $objUsuario->cargar($row['idusuario']);
                    $objUsuario->setUsuarioId($row['idusuario']);
                    $objRol = new Rol();
                    $objRol->setIdRol($row['idrol']);
                    $objRol->cargar($row['idrol']);
                    $this->setear($objUsuario, $objRol);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("UsuarioRol->cargar: ".$base->getError());
        }
        return $resp;
    }

    // Insertar en la base de datos
    public function insertar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO UsuarioRol (idusuario, idrol) VALUES ('" . $this->getobjUsuario()->getUsuarioId() ."', '" . $this->getobjRol()->getIdRol() . "')";
        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $resp = true;
                } else {
                    $this->setMensajeOperacion("UsuarioRol->insertar: " . $base->getError());
                }
            } else {
                $this->setMensajeOperacion("UsuarioRol->insertar: " . $base->getError());
            }
        } catch (PDOException $e) {
            $this->setMensajeOperacion("UsuarioRol->insertar: " . $e->getMessage());
        }
        return $resp;
    }

    // Modificar
    public function modificar(){
        $resp = false;
        return $resp;
    }

    // Eliminar
    public function eliminar(){
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM UsuarioRol WHERE idusuario=".$this->getobjUsuario()->getUsuarioId()." AND idrol=".$this->getobjRol()->getIdRol();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("UsuarioRol->eliminar: ".$base->getError());
            }
        } else {
            $this->setMensajeOperacion("UsuarioRol->eliminar: ".$base->getError());
        }
        return $resp;
    }

    // Listar registros
    public static function listar($parametro=""){
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM UsuarioRol";
        if ($parametro != "") {
            $sql .= ' WHERE '.$parametro;
        }
        $res = $base->Ejecutar($sql);
        if($res > -1){
            if($res > 0){
                while ($row = $base->Registro()){
                    $obj = new UsuarioRol();
                    $obj->getobjUsuario()->setUsuarioId($row['idusuario']);
                    $obj->getobjrol()->setIdRol($row['idrol']);
                    $obj->cargar();
                    array_push($arreglo, $obj);
                }
            }
        } else {
            error_log("UsuarioRol->listar: ".$base->getError());
        }
        return $arreglo;
    }
}

?>