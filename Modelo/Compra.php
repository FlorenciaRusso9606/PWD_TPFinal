<?php
class Compra {
    private $idcompra;
    private $cofecha;
    private $objUsuario;
    private $mensajeoperacion;


    /**
     * @return mixed
     */
    public function getidcompra() {
        return $this->idcompra;
    }

    /**
     * @param mixed $idcompra
     */
    public function setidcompra($idcompra) {
        $this->idcompra = $idcompra;
    }

    /**
     * @return mixed
     */
    public function getcofecha() {
        return $this->cofecha;
    }

    /**
     * @param mixed $cofecha
     */
    public function setcofecha($cofecha) {
        $this->cofecha = $cofecha;
    }



    /**
     * @return mixed
     */
    public function getobjUsuario() {
        return $this->objUsuario;
    }

    /**
     * @param mixed $objUsuario
     */
    public function setobjUsuario($objUsuario) {
        $this->objUsuario = $objUsuario;
    }

    /**
     * @return string
     */
    public function getMensajeoperacion() {
        return $this->mensajeoperacion;
    }

    /**
     * @param string $mensajeoperacion
     */
    public function setMensajeoperacion($mensajeoperacion) {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function __construct() {
        $this->idcompra = "";
        $this->cofecha = "";
        $this->objUsuario = null;
        $this->mensajeoperacion = "";
    }

    public function setear($idcompra, $cofecha, $objUsuario) {
        $this->setidcompra($idcompra);
        $this->setcofecha($cofecha);
        $this->setobjUsuario($objUsuario);
    }


    public function cargar() {
        $resp = false;
        $base = new BaseDatos();
        $idcompra = $this->getidcompra();

        if ($idcompra) {
            $sql = "SELECT * FROM compra WHERE idcompra = " . $idcompra;
            try {
                if ($base->Iniciar()) {
                    $res = $base->Ejecutar($sql);
                    if ($res > -1) {
                        if ($res > 0) {
                            $row = $base->Registro();
                            $this->setidcompra($row['idcompra']);
                            $this->setcofecha($row['cofecha']);
                            $this->objUsuario = new Usuario();
                            $this->objUsuario->setUsuarioId($row['idusuario']);
                            $this->objUsuario->cargar();
                            $resp = true;
                        }
                    }
                } else {
                    $this->setMensajeOperacion("compra->cargar: " . $base->getError());
                }
            } catch (PDOException $e) {
                $this->setMensajeOperacion("compra->cargar: " . $e->getMessage());
            }
        } else {
            $this->setMensajeOperacion("compra->cargar: idcompra no está establecido.");
        }
        return $resp;
    }

    public function insertar() {
        $resp = false;
        $base = new BaseDatos();
        $usuario = $this->getObjUsuario();
        if ($usuario != null) {
            $sql = "INSERT INTO compra (cofecha, idusuario) VALUES ('" . $this->getcofecha() . "', '" . $this->getObjUsuario() . "')";
            try {
                if ($base->Iniciar()) {
                    if ($base->Ejecutar($sql)) {
                        $resp = true;
                    } else {
                        $this->setMensajeOperacion("compra->insertar: " . $base->getError());
                    }
                } else {
                    $this->setMensajeOperacion("compra->insertar: " . $base->getError());
                }
            } catch (PDOException $e) {
                $this->setMensajeOperacion("compra->insertar: " . $e->getMessage());
            }
        } else {
            $this->setMensajeOperacion("compra->insertar: Usuario no está establecido.");
        }
        return $resp;
    }

    public function modificar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE compra SET cofecha='" . $this->getcofecha() . "', idusuario=" . $this->getobjUsuario()->getUsuarioId() . " WHERE idcompra=" . $this->getidcompra();

        // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compra->modificar 1: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compra->modificar 2: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar() {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM compra WHERE idcompra =" . $this->getidcompra();
        // echo $sql;
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setmensajeoperacion("compra->eliminar: " . $base->getError());
            }
        } else {
            $this->setmensajeoperacion("compra->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public  function listar($parametro = "") {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM compra";
        if ($parametro != "") {
            $sql .= ' WHERE ' . $parametro;
        }

        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                while ($row = $base->Registro()) {
                    $obj = new Compra();
                    $objUsuario = null;
                    if (!empty($row['idusuario'])) {
                        $objUsuario = new Usuario();
                        $objUsuario->setUsuarioId($row['idusuario']);
                        $objUsuario->cargar();
                    }
                    $obj->setear($row['idcompra'], $row['cofecha'], $objUsuario);
                    $arreglo[] = $obj;
                }
            }
        }
        return $arreglo;
    }
}
