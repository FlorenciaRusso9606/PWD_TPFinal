<?php

class Usuario
{
    private $idusuario;
    private $usmail;
    private $uspass;
    private $usnombre;
    private $usdeshabilitado;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idusuario = "";
        $this->usmail = "";
        $this->uspass = "";
        $this->usnombre = "";
        $this->usdeshabilitado = "";
        $this->mensajeoperacion = "";
    }

    public function getUsuarioId()
    {
        return $this->idusuario;
    }
    public function setUsuarioId($idusuario)
    {
        $this->idusuario = $idusuario;
    }


    public function getUsuarioEmail()
    {
        return $this->usmail;
    }
    public function setUsuarioEmail($usmail)
    {
        $this->usmail = $usmail;
    }

    public function getUsuarioPassword()
    {
        return $this->uspass;
    }
    public function setUsuarioPassword($uspass)
    {
        $this->uspass = $uspass;
    }

    public function getUsuarioNombre()
    {
        return $this->usnombre;
    }
    public function setUsuarioNombre($usnombre)
    {
        $this->usnombre = $usnombre;
    }

    public function getUsuarioDeshabilitado()
    {
        return $this->usdeshabilitado;
    }
    public function setUsuarioDeshabilitado($usdeshabilitado)
    {
        $this->usdeshabilitado = $usdeshabilitado;
    }

    public function getMensajeOperacion()
    {
        return $this->mensajeoperacion;
    }
    public function setMensajeOperacion($valor)
    {
        $this->mensajeoperacion = $valor;
    }

    public function setear($idusuario, $usnombre, $uspass, $usmail, $usdeshabilitado)
    {
        $this->setUsuarioId($idusuario);
        $this->setUsuarioNombre($usnombre);
        $this->setUsuarioPassword($uspass);
        $this->setUsuarioEmail($usmail);
        $this->setUsuarioDeshabilitado($usdeshabilitado);
    }

    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuario WHERE idusuario = '" . $this->getUsuarioId() . "'";
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $this->setear($row['idusuario'], $row['usnombre'], $row['uspass'], $row['usmail'], $row['usdeshabilitado']);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeOperacion("usuario->cargar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO usuario (usmail, uspass, usnombre, usdeshabilitado) VALUES ('" . $this->getUsuarioEmail() . "', '" . $this->getUsuarioPassword() . "', '" . $this->getUsuarioNombre() . "', '" . $this->getUsuarioDeshabilitado() . "')";
        try {
            if ($base->Iniciar()) {
                // Cambia la ejecución de la consulta a Ejecutar() como lo tenías.
                if ($base->Ejecutar($sql)) {
                    // Obtener el ID de la última inserción
                    $idUsuario = $base->lastInsertId();
                    if ($idUsuario) {
                        $this->setUsuarioId($idUsuario);
                        $resp = true;
                    }
                } else {
                    $this->setMensajeOperacion("usuario->insertar: " . $base->getError());
                }
            } else {
                $this->setMensajeOperacion("usuario->insertar: " . $base->getError());
            }
        } catch (PDOException $e) {
            $this->setMensajeOperacion("usuario->insertar: " . $e->getMessage());
        }
        return $resp;
    }


    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        if ($this->getUsuarioDeshabilitado() != NULL) {

            $sql = "UPDATE usuario SET usnombre='" . $this->getUsuarioNombre() . "', uspass='" . $this->getUsuarioPassword() . "', usmail= '" . $this->getUsuarioEmail() . "' , usdeshabilitado = '" . $this->getUsuarioDeshabilitado() . "'  WHERE idusuario = " . $this->getUsuarioId();
        } else {

            $sql = "UPDATE usuario SET usnombre = '" . $this->getUsuarioNombre() . "', uspass = '" . $this->getUsuarioPassword() . "', usmail = '" . $this->getUsuarioEmail() . "', usdeshabilitado = NULL WHERE idusuario = " . $this->getUsuarioId();
        }


        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("Tabla->modificar: {$base->getError()}");
            }
        } else {
            $this->setMensajeOperacion("Tabla->modificar: {$base->getError()}");
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM usuario WHERE idusuario=" . $this->getUsuarioId() . "";
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeOperacion("usuario->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeOperacion("usuario->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($parametro = "")
    {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM usuario";
        if ($parametro != "") {
            $sql .= ' WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $base->Registro()) {
                    $obj = new Usuario();
                    $obj->setUsuarioId($row['idusuario']);
                    $obj->cargar();
                    array_push($arreglo, $obj);
                }
            }
        } else {
            error_log("usuario->listar: " . $base->getError());
        }
        return $arreglo;
    }
}
