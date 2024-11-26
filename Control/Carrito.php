<?php
include_once "../configuracion.php";

class Carrito
{
    
        public function agregarProducto($param)
        {
            $productoAgregado = false;
            $session = new Session;
            $carrito = $session->getCarrito() ?? [];
            $abmProducto = new AbmProducto;
            $productoEncontrado = false;
            $i = 0;
    
            // Verificar si el producto ya está en el carrito
            while ($i < count($carrito) && !$productoEncontrado) {
                if ($carrito[$i]["idproducto"] == $param["idproducto"]) {
                    // Producto encontrado, incrementar la cantidad
                    $carrito[$i]['cantidadproducto'] += $param['cantidad'];
                    $productoEncontrado = true;
                    $productoAgregado = true;
                }
                $i++;
            }
    
            // Si no se encontró el producto en el carrito, buscarlo y agregarlo
            if (!$productoEncontrado) {
                $productos = $abmProducto->buscar($param);
                if (!empty($productos)) {
                    $producto = $productos[0];
                    $nuevoItem = [
                        'idproducto' => $producto->getIdProducto(),
                        'nombre' => $producto->getProNombre(),
                        'precio' => $producto->getProPrecio(),
                        'cantidadproducto' => $param['cantidad']
                    ];
                    $carrito[] = $nuevoItem;
                    $productoAgregado = true;
                }
            }
    
            // Guardar el carrito actualizado en la sesión
            $session->setCarrito($carrito);
    
            return $productoAgregado;
        }
    
        public function eliminarProducto($param)
        {
            $session = new Session;
            $carrito = $session->getCarrito() ?? [];
            $productoEncontrado = false;
            $i = 0;
    
            // Recorrer el carrito para encontrar y eliminar el producto
            while ($i < count($carrito) && !$productoEncontrado) {
                if ($carrito[$i]["idproducto"] == $param["idproducto"]) {
                    unset($carrito[$i]);
                    $productoEncontrado = true;
                }
                $i++;
            }
    
            if ($productoEncontrado) {
                // Reindexar el array y guardar el carrito actualizado
                $carrito = array_values($carrito);
                $session->setCarrito($carrito);
            }
    
            return $productoEncontrado;
        }
    
        public function obtenerCarrito()
        {
            $session = new Session();
            return $session->getCarrito() ?? [];
        }
    }
    
