<?php
include_once "../configuracion.php";

class Carrito
{

    public function agregarProducto($param)
    {
        echo "<br>Entro a agregarProducto";
        $productoAgregado = false;
        $session = new Session;
        $carrito = $session->getCarrito();
        $abmProducto = new AbmProducto;
        $productoEncontrado = false;
        $i = 0;
        if ($carrito != []) {

            //Recorre el carrito para ver si el producto ya esta agregado

            while ($i < count($carrito) && $productoEncontrado == true) {
    
                if ($carrito[$i]["idproducto"] == $param["idproducto"]) {
                    //Agrega más cantidad al item en el carrito
                    $carrito[$i]['cantidadproducto'] += $param['cantidad'];
                    $productoEncontrado = true;
                    $productoAgregado = true;
                }
                //Si el producto no está en el carrito lo agrega
                if (!$productoEncontrado) {
                    $productos = $abmProducto->buscar($param);
                    if (!empty($productos)) {
                        $producto = $productos[0]; 
                        $nuevoItem = [
                            'idproducto' => $producto->getIdProducto(),
                            'nombre' => $producto->getNombre(),
                            'precio' => $producto->getPrecio(),
                            'cantidadproducto' => $param['cantidad']
                        ];
                        $carrito[] = $nuevoItem;
                        $productoAgregado = true;
                    }
                    return $productoAgregado;
                }
                else{
                    echo "no devuelve nada";
                }
            }
        }
        //Setea el carrito
        $session->setCarrito($carrito);
    }


    public function eliminarProducto($param)
    {
        $session = new Session;
        $carrito = $session->getCarrito();
        $productoEncontrado = false;
        $i = 0;
        //Verifica que el array no esté vacío
        if ($carrito != []) {
            //Recorre el carrito para ver si el producto ya esta agregado
            while ($i < count($carrito) && $productoEncontrado == true) {
                //si no anda, agregar el indice $param[$i] 
                if ($carrito[$i]["idProducto"] == $param["idProducto"]) {
                    $carrito[$i]["cantidadProducto"] = $param["cantidad"];
                    unset($carrito[$i]);
                    $productoEncontrado = true;
                    // Reindexamos el array y guardamos en la sesión

                }
                $i++;
            }
        }
        //Reindexa el carrito
        $carrito = array_values($carrito);
        //Setea el carrito
        $session->setCarrito($carrito);
    }


    public function obtenerCarrito() {
        $session = new Session();
        return $session->getCarrito();
    }
}





// public function agregarProducto($param)
// 	{
// 		if (isset($param["idProducto"])) {
// 			if (!isset($_SESSION["carrito"])) {
// 				$_SESSION["carrito"] = [];
// 			}
// 			$existeProd = false;
// 			$i = 0;
// 			$carrito = $_SESSION["carrito"];
// 			// mostrarArray($carrito);
// 			if ($carrito != []) {

// 				do {
// 					if ($carrito[$i]["idProducto"] == $param["idProducto"]) {
// 						$existeProd = true;
// 					}
// 					$i++;
// 				} while ($i < count($carrito) && $existeProd == false);
// 			}
// 			if (!$existeProd) {
// 				if (!isset($param["cantidadProducto"])) {
// 					$param["cantidadProducto"] = 1;
// 				}
// 				array_push($_SESSION["carrito"], $param);
// 			}
// 		}
// 		return $existeProd;
// 	}