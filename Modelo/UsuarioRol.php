<?php

class UsuarioRol
{
    private $objUsuario;
    private $objRol;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->objUsuario = new Usuario();
        $this->objRol = new Rol();
    }

    public function getObjUsuario()
    {
        return $this->objUsuario;
    }

    public function setObjUsuario($objUsuario)
    {
        $this->objUsuario = $objUsuario;
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

    public function setear($objUsuario, $objRol)
    {
        $this->setObjUsuario($objUsuario);
        $this->setObjRol($objRol);
    }

    public function setearConClave($idusuario, $idrol)
    {
        $this->getObjRol()->setIdRol($idrol);
        $this->getObjUsuario()->setUsuarioId($idusuario);
    }

    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $idusuario = $this->getObjUsuario()->getUsuarioId();
        $idrol = $this->getObjRol()->getIdRol();

        if ($idusuario && $idrol) {
            $sql = "SELECT * FROM usuariorol WHERE idusuario = " . $idusuario . " AND idrol = " . $idrol;
            if ($base->Iniciar()) {
                $res = $base->Ejecutar($sql);
                if ($res > -1) {
                    if ($res > 0) {
                        $row = $base->Registro();
                        $objUsuario = new Usuario();
                        $objUsuario->setUsuarioId($row['idusuario']);
                        $objUsuario->cargar();
                        $objRol = new Rol();
                        $objRol->setIdRol($row['idrol']);
                        $objRol->cargar();
                        $this->setear($objUsuario, $objRol);
                        $resp = true;
                    }
                }
            } else {
                $this->setMensajeOperacion("usuariorol->cargar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("usuariorol->cargar: idusuario o idrol no están establecidos.");
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO usuariorol (idusuario, idrol) VALUES ('" . $this->getObjUsuario()->getUsuarioId() . "', '" . $this->getObjRol()->getIdRol() . "')";

        try {
            if ($base->Iniciar()) {
                if ($base->Ejecutar($sql)) {
                    $resp = true;
                } else {
                    $this->setMensajeOperacion("usuariorol->insertar: " . $base->getError());
                }
            } else {
                $this->setMensajeOperacion("usuariorol->insertar: " . $base->getError());
            }
        } catch (PDOException $e) {
            $this->setMensajeOperacion("usuariorol->insertar: " . $e->getMessage());
        }
        return $resp;
    }

    public function modificar()
    {

        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE usuariorol SET idusuario=" . $this->getObjUsuario()->getUsuarioId() . ", idrol=" . $this->getObjRol()->getIdRol() . " WHERE idusuario=" . $this->getObjUsuario()->getUsuarioId() . " AND idrol=" . $this->getObjRol()->getIdRol();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("usuariorol->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("usuariorol->modificar: " . $base->getError());
        }

        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM usuariorol WHERE idusuario=" . $this->getObjUsuario()->getUsuarioId() . " AND idrol=" . $this->getObjRol()->getIdRol();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("usuariorol->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("usuariorol->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($parametro = "")
    {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuariorol";
        if ($parametro != "") {
            $sql .= ' WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new UsuarioRol();
                    $obj->getObjUsuario()->setUsuarioId($row['idusuario']);
                    $obj->getObjRol()->setIdRol($row['idrol']);
                    $obj->cargar();
                    array_push($arreglo, $obj);
                }
            }
        } else {
            error_log("usuariorol->listar: " . $base->getError());
        }
        return $arreglo;
    }
}
