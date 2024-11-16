<?php

class ControlCompra{

  
    /**
     * Da de alta una compra
     * @return bool
     */
    public function getInicioCompra(){
        //Obtener datos de la compra 
        $idcompra= null;
        $session = new Session();
        $abmCompra = new AbmCompra();
        $idusuario = $session->getUsuario();
        $param['idusuario']= $idusuario;
        $param["cofecha"] = date('Y-m-d H:i:s');
        $alta= $abmCompra->alta($param);
        if($alta){
            $compra= $abmCompra->buscar($param);
            if(count($compra)>0){
                $idcompra = $compra[0]->getidcompra();
            }
        }
        return $idcompra;    
    }
	
    /**
     * Modifica los datos de un producto
     * @param Producto $objProducto
     * @return Array
     */
    public function modDatosProducto($objProducto) {
        $datosModProd = [
          'idproducto' => $objProducto->getIdProducto(),
          'pronombre' => $objProducto->getProNombre(),
          'prodetalle' =>$objProducto->getProDetalle(),
          'procantstock' => $objProducto->getProCantStock(),
          'proprecio' => $objProducto->getProPrecio()
        ];
        return $datosModProd;
      }

      /**
       * Confirmar Compra
       * @return int
       * */ 
      public function confirmarCompra() {
        $compraExitosa = false;
        $session = new Session;
        $carrito = $session->getCarrito();
    
        if (count($carrito) > 0) {
    
          $idcompra = $this->getInicioCompra();
    
          $precioTotal = 0;
          /* Si se da de alta la compra */
          if ($idcompra!==null) {
            $i = 0;
            $j = 0;
            $falloCompraItem = false;
    
    
            /* Ciclo el carrito y voy a crear un compra item por cada producto */
            do {
              $producto = $carrito[$i];
              $abmProducto = new AbmProducto();
              $objProducto = $abmProducto->buscar($producto["idProducto"])[0];
              $precioTotal = $objProducto->getProPrecio() * $producto["cantidadProducto"];
    
              $abmCompraItem = new AbmCompraItem();
    
              $datosCompraItem["idproducto"] = $producto["idProducto"];
              $datosCompraItem["cicantidad"] = $producto["cantidadProducto"];
              $datosCompraItem["idcompra"] = $idcompra;
              $datosCompraItem["cipreciototal"] = $precioTotal;
    
              /* Si se da de alta el compra item, voy a modificar el stock del producto y modificarlo en la bd */
              if ($abmCompraItem->alta($datosCompraItem)) {
                $cantActual = $objProducto->getProCantStock();
                $nuevaCant = $cantActual - $datosCompraItem["cicantidad"];
                $objProducto->setProCantStock($nuevaCant);
    
                $datosMod = $this->modDatosProducto($objProducto);
                $abmProducto->modificacion($datosMod);
    
                $j++;
              } else $falloCompraItem = true;
    
              $i++;
            } while ($i < count($carrito) && $falloCompraItem == false);
    
    
            /* Si la cantidad de productos modificados fue igual a la cantidad de productos en el carrito, creo el estado de la compra */
            $compraExitosa = $this->verificacionCompraItems($j, $i, $idcompra);
          };
        };
    
        $msj = ($compraExitosa) ? 5 : 4;
    
        return $compraExitosa;
      }
    //Cambiar estado
    //Eliminar compra
    
    //Modificar stock

    //Devolver Compras ?

	//Cancelar
	public function cancelarCompra($objIdProducto)
	{
		$respuesta = false;

        



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
        $altaCompraEstado = false;
        $session = new Session;
        if ($j == $i) {
          $datosCompraEstado = [
            "idcompra" => $idcompra,
            "idcompraestadotipo" => 1, //Compra tipo 1 = "Iniciada"
            "cefechaini" => date('Y-m-d H:i:s'),
            "cefechafin" => "null",
          ];
          $abmCompraEstado = new AbmCompraEstado();
          $altaCompraEstado =  $abmCompraEstado->alta($datosCompraEstado);
          /* Vacio el carrito */
          $carrito = [];
          $session->setCarrito($carrito);
          /* Si no es igual la cantidad, voy a buscar cada compra item y lo voy a dar de baja */
        } else if ($j < $i || $altaCompraEstado == false) {
          $abmCompraItem = new AbmCompraItem();
          $arrCompraItems = $abmCompraItem->buscar($idcompra);
          foreach ($arrCompraItems as $compraItem) {
            $compraItem->baja(["idcompraitem" => $compraItem->getIdCompraItem()]);
          }
          $compraExitosa = false;
        }
    
        return $compraExitosa;
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