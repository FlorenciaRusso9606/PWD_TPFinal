<?php
class CompraItem
{
    private $idcompraitem;
    private $objProducto;
    private $objCompra;
    private $cicantidad;
    private $mensajeoperacion;

    public function __construct()
    {
        $this->idcompraitem = "";
        $this->objProducto;
        $this->objCompra;
        $this->cicantidad = "";
        $this->mensajeoperacion = "";
    }

    public function getidcompraitem()
    {
        return $this->idcompraitem;
    }

    public function setidcompraitem($idcompraitem)
    {
        $this->idcompraitem = $idcompraitem;
    }

    public function getobjProducto()
    {
        return $this->objProducto;
    }

    public function setobjProducto($objProducto)
    {
        $this->objProducto = $objProducto;
    }

    public function getobjCompra()
    {
        return $this->objCompra;
    }

    public function setobjCompra($objCompra)
    {
        $this->objCompra = $objCompra;
    }

    public function getcicantidad()
    {
        return $this->cicantidad;
    }

    public function setcicantidad($cicantidad)
    {
        $this->cicantidad = $cicantidad;
    }

    public function getMensajeoperacion()
    {
        return $this->mensajeoperacion;
    }

    public function setMensajeoperacion($mensajeoperacion)
    {
        $this->mensajeoperacion = $mensajeoperacion;
    }

    public function setear($idcompraitem, $objProducto, $objCompra, $cicantidad)
    {
        $this->setidcompraitem($idcompraitem);
        $this->setobjProducto($objProducto);
        $this->setobjCompra($objCompra);
        $this->setcicantidad($cicantidad);
    }

    public function cargar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraitem WHERE idcompraitem = " . $this->getidcompraitem();
        if ($base->Iniciar()) {
            $res = $base->Ejecutar($sql);
            if ($res > 0) {
                $row = $base->Registro();
                $objCompra = null;
                $objProducto = null;

                if (!empty($row['idcompra'])) {
                    $objCompra = new Compra();
                    $objCompra->setidcompra($row['idcompra']);
                    $objCompra->cargar();
                }
                if (!empty($row['idproducto'])) {
                    $objProducto = new Producto();
                    $objProducto->setIdProducto($row['idproducto']);
                    $objProducto->cargar();
                }
                $this->setear($row['idcompraitem'], $objProducto, $objCompra, $row['cicantidad']);
                $resp = true;
            }
        } else {
            $this->setMensajeoperacion("compraitem->cargar: " . $base->getError()[2]);
        }
        return $resp;
    }

    public function insertar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "INSERT INTO compraitem (idproducto, idcompra, cicantidad) VALUES ("
            . $this->getobjProducto()->getIdProducto() . ", "
            . $this->getobjCompra()->getidcompra() . ", "
            . $this->getcicantidad() . ")";
        if ($base->Iniciar()) {
            if ($elid = $base->Ejecutar($sql)) {
                $this->setidcompraitem($elid);
                $resp = true;
            } else {
                $this->setMensajeoperacion("compraitem->insertar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("compraitem->insertar: " . $base->getError());
        }
        return $resp;
    }

    public function modificar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "UPDATE compraitem SET "
            . "idproducto = " . $this->getobjProducto()->getIdProducto() . ", "
            . "idcompra = " . $this->getobjCompra()->getidcompra() . ", "
            . "cicantidad = " . $this->getcicantidad() . " "
            . "WHERE idcompraitem = " . $this->getidcompraitem();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("compraitem->modificar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("compraitem->modificar: " . $base->getError());
        }
        return $resp;
    }

    public function eliminar()
    {
        $resp = false;
        $base = new BaseDatos();
        $sql = "DELETE FROM compraitem WHERE idcompraitem = " . $this->getidcompraitem();
        if ($base->Iniciar()) {
            if ($base->Ejecutar($sql)) {
                $resp = true;
            } else {
                $this->setMensajeoperacion("compraitem->eliminar: " . $base->getError());
            }
        } else {
            $this->setMensajeoperacion("compraitem->eliminar: " . $base->getError());
        }
        return $resp;
    }

    public static function listar($parametro = "")
    {
        $arreglo = [];
        $base = new BaseDatos();
        $sql = "SELECT * FROM compraitem";
        if ($parametro != "") {
            $sql .= " WHERE " . $parametro;
        }
        $res = $base->Ejecutar($sql);
        if ($res > 0) {
            while ($row = $base->Registro()) {
                $obj = new CompraItem();
                $objCompra = null;
                $objProducto = null;

                if (!empty($row['idcompra'])) {
                    $objCompra = new Compra();
                    $objCompra->setidcompra($row['idcompra']);
                    $objCompra->cargar();
                }
                if (!empty($row['idproducto'])) {
                    $objProducto = new Producto();
                    $objProducto->setIdProducto($row['idproducto']);
                    $objProducto->cargar();
                }
                $obj->setear($row['idcompraitem'], $objProducto, $objCompra, $row['cicantidad']);
                array_push($arreglo, $obj);
            }
        }
        return $arreglo;
    }
}
