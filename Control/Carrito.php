<?php
include_once "../configuracion.php";

class Carrito
{

    public function agregarProducto($param)
    {
        echo "<br>Entro a agregarProducto";
        $session = new Session;
        $carrito = $session->getCarrito();
        $abmProducto = new AbmProducto;
        $bandera = false;
        $i = 0;
        if ($carrito != []) {
            
            //Recorre el carrito para ver si el producto ya esta agregado
          
            while ($i < count($carrito) && $bandera == true) {
                //si no anda, agregar el indice $param[$i] 
                if ($carrito[$i]["idProducto"] == $param["idProducto"]) {
                    $carrito[$i]["cantidadProducto"] = $carrito[$i]["cantidadProducto"] + $param["cantidad"];
                    $bandera = true;
                }
                if ($bandera == false) {
                    $producto = $abmProducto->buscar($param);
                    $carrito[]= $producto;
                    array_push($carrito, $producto);
                    //huevada para que cargue carrito
                    
                }
                $i++;
            }
            

        }
        $producto = $abmProducto->buscar($param);
        $carrito[]= $producto;
        echo "<br>Carrito<br>";
        var_dump($carrito);
        $session->setCarrito($carrito);
    }

    public function eliminarProducto($param)
    {
        $session = new Session;
        $carrito = $session->getCarrito();
        $bandera = false;
        $i = 0;
        //Verifica que el array no esté vacío
        if ($carrito != []) {
            //Recorre el carrito para ver si el producto ya esta agregado
            while ($i < count($carrito) && $bandera == true) {
                //si no anda, agregar el indice $param[$i] 
                if ($carrito[$i]["idProducto"] == $param["idProducto"]) {
                    $carrito[$i]["cantidadProducto"] = $param["cantidad"];
                    unset($carrito[$i]);
                    $bandera = true;
                    $session->setCarrito($carrito);
                }
                $i++;
            }
        }
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