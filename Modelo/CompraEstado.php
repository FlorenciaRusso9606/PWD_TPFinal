<?php
class CompraEstado
{
    private $idcompraestado;
    private $objCompraEstadoTipo;
    private $objCompra;
    private $cefechaini;
    private $cefechafin;
    private $mensajeoperacion;


    /**
     * @return mixed
     */
    public function getidcompraestado()
    {
        return $this->idcompraestado;
    }

    /**
     * @param mixed $idcompraestado
     */
    public function setidcompraestado($idcompraestado)
    {
        $this->idcompraestado = $idcompraestado;
    }

    /**
     * @return mixed
     */
    public function getobjCompraEstadoTipo()
    {
        return $this->objCompraEstadoTipo;
    }

    /**
     * @param mixed $objCompraEstadoTipo
     */
    public function setobjCompraEstadoTipo($objCompraEstadoTipo)
    {
        $this->objCompraEstadoTipo = $objCompraEstadoTipo;
    }



    /**
     * @return mixed
     */
    public function getobjCompra()
    {
        return $this->objCompra;
    }

    /**
     * @param mixed $objCompra
     */
    public function setobjCompra($objCompra)
    {
        $this->objCompra = $objCompra;
    }

    /**
     * @return mixed
     */
    public function getcefechaini()
    {
        return $this->cefechaini;
    }

    /**
     * @param mixed $cefechaini
     */
    public function setcefechaini($cefechaini)
    {
        $this->cefechaini = $cefechaini;
    }


    /**
     * @return mixed
     */
    public function getcefechafin()
    {
        return $this->cefechafin;
    }

    /**
     * @param mixed $cefechafin
     */
    public function setcefechafin($cefechafin)
    {
        $this->cefechafin = $cefechafin;
    }


    /**
     * @return string
     */
    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    /**
     * @param string $mensajeoperacion
     */
    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function __construct()
    {
        $this->idcompraestado = "";
        $this->objCompraEstadoTipo = null;
        $this->objCompra = null;
        $this->cefechaini = "";
        $this->cefechafin = "";
        $this->mensajeoperacion = "";
    }

    public function setear($idcompraestado, $objCompraEstadoTipo, $objCompra, $cefechaini, $cefechafin)
    {
        $this->setidcompraestado($idcompraestado);
        $this->setobjCompraEstadoTipo($objCompraEstadoTipo);
        $this->setobjCompra($objCompra);
        $this->setcefechaini($cefechaini);
        $this->setcefechafin($cefechafin);
    }


    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestado WHERE idcompraestado = " . $this->getidcompraestado();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > -1) {
                if ($res > 0) {
                    $row = $base->Registro();
                    $objCompra = null;
                    $objCompraEstadoTipo = null;
                    if ($row['idcompra'] != null || $row['idcompra'] != '') {
                        $objCompra = new Compra();
                        $objCompra->setidcompra($row['idcompra']);
                        $objCompra->cargar();
                    }
                    if ($row['idcompraestadotipo'] != null || $row['idcompraestadotipo'] != '') {
                        $objCompraEstadoTipo = new CompraEstadoTipo();
                        $objCompraEstadoTipo->setidcompraestadotipo($row['idcompraestadotipo']);
                        $objCompraEstadoTipo->cargar();
                    }
                    $this->setear($row['idcompraestado'], $objCompraEstadoTipo, $objCompra, $row['cefechaini'], $row['cefechafin']);
                    $resp = true;
                }
            }
        } else {
            $this->setMensajeoperacion("compraestado->cargar: " . $base->getError());
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO compraestado (idcompra, idcompraestadotipo, cefechaini, cefechafin) ";
        $sql .= "VALUES (" . $this->getobjCompra()->getidcompra() . ", "
            . $this->getobjCompraEstadoTipo()->getidcompraestadotipo() .  ", '"
            . $this->getcefechaini() . "', '"
            . $this->getcefechafin() . "')";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setidcompraestado($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("compraestado->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("compraestado->insertar: " . $base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE compraestado SET idcompraestadotipo=" . $this->getobjCompraEstadoTipo()->getidcompraestadotipo() . ", idcompra=" . $this->getobjCompra()->getidcompra() . ", cefechaini='" . $this->getcefechaini() . "', cefechafin='" . $this->getcefechafin() . "' WHERE idcompraestado=" . $this->getidcompraestado();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("compraestado->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("compraestado->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM compraestado WHERE idcompraestado=" . $this->getidcompraestado();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("compraestado->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("compraestado->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static  function listar($parametro = "")
    {
        $arreglo = array();
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraestado ";
        //   echo $sql;
        if ($parametro != "") {
            $sql .= 'WHERE ' . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {

                while ($row = $base->Registro()) {
                    $obj = new CompraEstado();
                    $objCompra = null;
                    if ($row['idcompra'] != null) {
                        $objCompra = new Compra();
                        $objCompra->setidcompra($row['idcompra']);
                        $objCompra->cargar();
                    }
                    if ($row['idcompraestadotipo'] != null) {
                        $objCompraEstadoTipo = new CompraEstadoTipo();
                        $objCompraEstadoTipo->setidcompraestadotipo($row['idcompraestadotipo']);
                        $objCompraEstadoTipo->cargar();
                    }
                    $obj->setear($row['idcompraestado'], $$objCompraEstadoTipo, $row['medescripcion'], $objCompra, $row['medeshabilitado']);
                    array_push($arreglo, $obj);
                }
            }
        }

        return $arreglo;
    }
}
