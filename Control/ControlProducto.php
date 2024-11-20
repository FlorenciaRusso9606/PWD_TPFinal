<?php

class ControlProducto{

    public function agregarProducto($datos) {
        $ambProducto = new AbmProducto();
        $resp =false;
        if (!$ambProducto->buscar($datos)) {
          $resp = $ambProducto->alta($datos);
          
        } 
        return $resp;
      }

    public function modificarPorducto($datos){
      $resp= null;
      echo "Entró a modificaciones";
      $ambProducto = new AbmProducto();
      if ($ambProducto->buscar($datos)) {
        $resp = $ambProducto->modificacion($datos);
    }
    return $resp;
  }


  public function realizarAccion($data) {
    // Convertir "null" a null en idproducto
    if (isset($data['idproducto']) && $data['idproducto'] === 'null') {
        $data['idproducto'] = null;
    }

    // Convertir procantstock a un entero
    if (isset($data['procantstock'])) {
        $data['procantstock'] = (int) $data['procantstock'];
    }

    // Convertir proprecio a un decimal (float)
    if (isset($data['proprecio'])) {
      $data['proprecio'] = (float) $data['proprecio'];
      
      // Formatearlo a dos decimales (simulando decimal(10,2))
      $data['proprecio'] = number_format($data['proprecio'], 2, '.', '');
    }



    $result  = ($data['idproducto'] !== null) ? $this->modificarPorducto($data) : $this->agregarProducto($data);
    return (bool)$result;
}


public function productoActual($data){
  $producto = null;
  $ambProducto= new AbmProducto;
  $arrayProd=$ambProducto->buscar($data);
  if(count($arrayProd)>0){
    $producto = $arrayProd[0];
  }
  return $producto;
}
}

