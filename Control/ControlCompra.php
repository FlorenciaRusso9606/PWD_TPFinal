<?php

class ControlCompra
{

  /**
   * Da de alta una compra
   * @return bool
   */
  public function getInicioCompra()
  {
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
  public function modDatosProducto($objProducto)
  {
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
  public function confirmarCompra()
  {
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

    return $compraExitosa;
  }



  /**
   * Verifica la cantidad de productos en el carrito con su
   * @param int $j Cantidad de productos modificados
   * @param int $i Cantidad de productos en el carrito
   * @param int $idcompra Id de la compra
   * @return bool
   */
  private function verificacionCompraItems($j, $i, $idcompra)
  {
    $compraExitosa = false; // Inicializar la variable
    $altaCompraEstado = false;
    $session = new Session();
    if ($j == $i) {
      $datosCompraEstado = [
        "idcompra" => $idcompra,
        "idcompraestadotipo" => 1, // Compra tipo 1 = "Iniciada"
        "cefechaini" => date('Y-m-d H:i:s'),
        "cefechafin" => date('0000-00-00 00:00:00') //el valor anterior era null
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
  public function buscarCompras($sesion)
  {
    $abmCompra = new AbmCompra();
    if ($sesion->getRol() == 2 || $sesion->getRol() == 1) {
      $arrCompras = $abmCompra->buscar("");
    } else {
      $datos["idusuario"] = $sesion->getUsuario();
      $arrCompras = $abmCompra->buscar($datos);
    }
    return $arrCompras;
  }
  public function cambiarEstado($param)
  {
    $respuesta = false;
    $abmCompraEstado = new AbmCompraEstado;
    $abmCompraItem = new AbmCompraItem;
    $abmProducto = new AbmProducto;
    $abmUsuario = new ABMUsuario;
    $controlMail = new ControladorMail;
    $controlPdf = new PDF();
    // Obtener el estado actual de la compra
    $compraEstado = $abmCompraEstado->buscar($param);
    if (empty($compraEstado)) {
      $ultimoEstado = 0;
      $respuesta = false; // La compra no existe o no tiene estado
    }

    //Obtiene el ultimo cambio de estado
    $ultimoEstado = count($compraEstado);


    // Preparar datos para actualizar el estado
    //Setear fechafin del estado anterior

    $datosEstadoAnterior = [
      'idcompraestado' => $compraEstado[$ultimoEstado - 1]->getidcompraestado(),
      'idcompra' => $param['idcompra'],
      'idcompraestadotipo' => $compraEstado[$ultimoEstado - 1]->getobjCompraEstadoTipo()->getidcompraestadotipo(),
      'cefechaini' => $compraEstado[$ultimoEstado - 1]->getcefechaini(),
      'cefechafin' => date('Y-m-d H:i:s')
    ];
    $modificacionEstadoAnterior = $abmCompraEstado->modificacion($datosEstadoAnterior);
    //Crear nuevo estado


    $datosEstadoPosterior = [
      'idcompra' => $param['idcompra'],
      'idcompraestadotipo' => $param['nuevoestado'],
      'cefechaini' => date('Y-m-d H:i:s'),
      'cefechafin' => date('0000-00-00 00:00:00')
    ];

    $arrCompraItem = $abmCompraItem->buscar(['idcompra' => $param['idcompra']]);
    if ($param['nuevoestado'] == 4) {
      foreach ($arrCompraItem as $compraItem) {
        $idProd["idproducto"] = $compraItem->getObjProducto()->getIdProducto();
        $objProducto = $abmProducto->buscar($idProd)[0];

        $datosProducto = [
          'idproducto' => $objProducto->getIdProducto(),
          'pronombre' => $objProducto->getProNombre(),
          'prodetalle' => $objProducto->getProDetalle(),
          'procantstock' => $objProducto->getProCantStock() + $compraItem->getCiCantidad(),
          'proprecio' => $objProducto->getProPrecio(),
        ];
        $abmProducto->modificacion($datosProducto);
      }
      $datosEstadoPosterior['cefechafin'] = date('Y-m-d H:i:s');
    }

    $modificacionEstadoActual = $abmCompraEstado->alta($datosEstadoPosterior);
    // En caso de cancelar compra devolver productos al stock
    //$abmCompra = new AbmCompra;
    //$compra = $abmCompra->buscar(['idcompra' => $param['idcompra']] )[0];


    // Actualizar el estado de la compra

    if ($modificacionEstadoActual && $modificacionEstadoAnterior) {

      $respuesta = true;
      // Obtener los ítems de la compra
      $arrCompraItem = $abmCompraItem->buscar(['idcompra' => $param['idcompra']]);
      $datosPDF['productos'] = [];

      foreach ($arrCompraItem as $compraItem) {
        $idProd = ['idproducto' => $compraItem->getObjProducto()->getIdProducto()];
        $objProducto = $abmProducto->buscar($idProd)[0];

        // Preparar datos del producto para el PDF
        $datosPDF['productos'][] = [
          'pronombre' => $objProducto->getProNombre(),
          'proprecio' => $objProducto->getProPrecio(),
          'cantidad' => $compraItem->getCiCantidad(),
        ];
      }

      // Obtener el usuario relacionado con la compra
      $compra = $compraEstado[0]->getObjCompra();
      $idusuario = $compra->getobjUsuario()->getUsuarioId();
      $usuario = $abmUsuario->buscar(['idusuario' => $idusuario])[0];

      $datosPDF['idusuario'] = $idusuario;
      $datosPDF['usnombre'] = $usuario->getUsuarioNombre();
      $datosPDF['usmail'] = $usuario->getUsuarioEmail();

      // Generar el PDF
      $pdfFilePath = $controlPdf->generarPdfCompra($datosPDF);

      if (file_exists($pdfFilePath)) {
        // Enviar correo al usuario
        $asunto = $param['nuevoestado'];
        $mensaje = "Nos dirigimos a usted con la intención de comunicarle que el estado de su compra ha cambiado. Adjuntamos el comprobante en PDF.";
        $controlMail->crearMail($asunto, $pdfFilePath, $usuario->getUsuarioEmail(), $usuario->getUsuarioNombre(), $mensaje);
      } else {
        echo "No se pudo encontrar el archivo PDF: $pdfFilePath";
      }
    }

    return $respuesta;
  }
}
