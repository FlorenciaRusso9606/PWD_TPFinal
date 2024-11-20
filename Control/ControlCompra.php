<?php

class ControlCompra {

    /**
     * Da de alta una compra
     * @return bool
     */
    public function getInicioCompra() {
        // Obtener datos de la compra 
        $idcompra = null;
        $session = new Session();
        $abmCompra = new AbmCompra();
        $idusuario = $session->getUsuario();

        $param['idusuario'] = $idusuario;
        $param["cofecha"] = date('Y-m-d H:i:s');

        $alta = $abmCompra->alta($param);
        if ($alta) {
            $compra = $abmCompra->buscar($param);
            if (count($compra) > 0) {
                $idcompra = $compra[0]->getidcompra();
            }
        }
        return $idcompra;
    }

    /**
     * Modifica los datos de un producto
     * @param Producto $objProducto
     * @return array
     */
    public function modDatosProducto($objProducto) {
        $datosModProd = [
            'idproducto' => $objProducto->getIdProducto(),
            'pronombre' => $objProducto->getProNombre(),
            'prodetalle' => $objProducto->getProDetalle(),
            'procantstock' => $objProducto->getProCantStock(),
            'proprecio' => $objProducto->getProPrecio()
        ];
        return $datosModProd;
    }

    /**
     * Confirmar Compra
     * @return int
     */
    public function confirmarCompra() {
        $compraExitosa = false;
        $session = new Session();
        $carrito = $session->getCarrito();

        if (count($carrito) > 0) {
            $idcompra = $this->getInicioCompra();

            $precioTotal = 0;
            if ($idcompra !== null) {
                $i = 0;
                $j = 0;
                $falloCompraItem = false;

                do {
                    $producto = $carrito[$i];
                    $abmProducto = new AbmProducto();
                    $objProducto = $abmProducto->buscar(['idproducto' => $producto["idproducto"]])[0];
                    $precioTotal = $objProducto->getProPrecio() * $producto["cantidadproducto"];

                    $abmCompraItem = new AbmCompraItem();

                    $datosCompraItem["idproducto"] = $producto["idproducto"];
                    $datosCompraItem["cicantidad"] = $producto["cantidadproducto"];
                    $datosCompraItem["idcompra"] = $idcompra;
                    $datosCompraItem["cipreciototal"] = $precioTotal;

                    if ($abmCompraItem->alta($datosCompraItem)) {
                        $cantActual = $objProducto->getProCantStock();
                        $nuevaCant = $cantActual - $datosCompraItem["cicantidad"];
                        $objProducto->setProCantStock($nuevaCant);

                        $datosMod = $this->modDatosProducto($objProducto);
                        $abmProducto->modificacion($datosMod);

                        $j++;
                    } else {
                        $falloCompraItem = true;
                    }

                    $i++;
                } while ($i < count($carrito) && $falloCompraItem == false);

                $compraExitosa = $this->verificacionCompraItems($j, $i, $idcompra);
            }
        }

        $msj = ($compraExitosa) ? 5 : 4;
        return $compraExitosa;
    }

    // Cambiar estado
    // Eliminar compra

    // Modificar stock

    // Devolver Compras ?

    // Cancelar
    public function cancelarCompra($param) {
        $respuesta = false;
        $param['idcompra']=$param['idcancelarcompra'];
            $abmCompra = new abmCompra;
            $abmEstado = new AbmCompraEstado;
            $abmCompraItem = new ABMCompraItem;
            $compra = $abmCompra->buscar($param);
            $idCompra = $compra[0]->getidcompra();
            $compraEstado = $abmEstado->buscar($idCompra);
            $arrCompraItem = $abmCompraItem->buscar($idCompra);
            $datos = [
                "idcompraestado" => $compraEstado[0]->getIdCompraEstado(),
                "idcompra" => $param["idcompracancelar"],
                "idcompraestadotipo" => 4,
                "cefechaini" => $compraEstado[0]->getCeFechaIni(),
                "cefechafin" =>  date("Y-m-d H:i:s"),
              ];
            foreach($arrCompraItem as $compraItem){
                $param['idproducto']= $compraItem->getobjProducto()->getIdProducto();
                $abmProducto = new AbmProducto;
                $objProducto = $abmProducto->buscar($param);
        
            $nuevaCant = $objProducto[0]->getProCantStock() + $compraItem->getCiCantidad();
            $objProducto[0]->setProCantStock($nuevaCant);

            $datosMod = $this->modDatosProducto($objProducto[0]);
            $respStock =$abmProducto->modificacion($datosMod);
        }
        if($respStock){
            $respuesta = $abmEstado->modificacion($datos);
        }
        return $respuesta;
    }
        
        
    

    /**
     * Verifica la cantidad de productos en el carrito con su
     * @param int $j Cantidad de productos modificados
     * @param int $i Cantidad de productos en el carrito
     * @param int $idcompra Id de la compra
     * @return bool
     */
    private function verificacionCompraItems($j, $i, $idcompra) {
        $compraExitosa = false; // Inicializar la variable
        $altaCompraEstado = false;
        $session = new Session();
        if ($j == $i) {
            $datosCompraEstado = [
                "idcompra" => $idcompra,
                "idcompraestadotipo" => 1, // Compra tipo 1 = "Iniciada"
                "cefechaini" => date('Y-m-d H:i:s'),
                "cefechafin" => date('1970-01-01 00:00:00') //el valor anterior era null
            ];
            $abmCompraEstado = new AbmCompraEstado();
            $altaCompraEstado = $abmCompraEstado->alta($datosCompraEstado);
            if ($altaCompraEstado) {
                $compraExitosa = true;
                /* Vacio el carrito */
                $carrito = [];
                $session->setCarrito($carrito);
            }
        } else if ($j < $i || $altaCompraEstado == false) {
            $abmCompraItem = new AbmCompraItem();
            $arrCompraItems = $abmCompraItem->buscar(["idcompra" => $idcompra]);
            foreach ($arrCompraItems as $compraItem) {
                $compraItem->baja(["idcompraitem" => $compraItem->getIdCompraItem()]);
            }
            $compraExitosa = false;
        }

        return $compraExitosa;
    }
    public function buscarCompras($sesion) {
        $abmCompra = new AbmCompra();
        if ($sesion->getRol() == 2) {
          $arrCompras = $abmCompra->buscar("");
        } else {
          $datos["idusuario"] = $sesion->getUsuario();
          $arrCompras = $abmCompra->buscar($datos);
        }
        return $arrCompras;
      }
public function cambiarEstado($param){
    $respuesta = null;
    $abmCompraEstado = new AbmCompraEstado;
    $compraEstado = $abmCompraEstado->buscar($param);
    $datoEstado=[
        'idcompraestado' => $compraEstado[0]->getidcompraestado(),
        'idcompra' => $param['idcompra'],
        'idcompraestadotipo' => $param['nuevoestado'],
        'cefechaini'=> $compraEstado[0]->getcefechaini(),
        'cefechafin' => $compraEstado[0]->getcefechafin()
    ];
    $respuesta = $abmCompraEstado->modificacion($datoEstado);
    return $respuesta;
}
    // public function mensajesCompraControl($num) {
    //     $mensajes = [
    //       /* Cambiar estado de compra*/
    //       0 => 'No se pudo cambiar el estado de compra',
    //       1 => 'El estado de la compra se cambio correctamente.',
    //       /* Cancelar compra */
    //       2 => 'Hubo un error al cancelar su compra.',
    //       3 => 'Compra cancelada correctamente.',
    //       /* Confirmar compra */
    //       4 => "Hubo un error al confirmar su compra",
    //       5 => "Compra confirmada correctamente.",
    //     ];
    //     return $mensajes[$num];
    //   }

}
?>