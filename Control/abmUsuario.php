<?php
class ABMUsuario
{

    public function abm($datos)
    {
        $resp = false;
        if ($datos['accion'] == 'editar') {
            if ($this->modificacion($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'borrar') {
            if ($this->baja($datos)) {
                $resp = true;
            }
        }
        if ($datos['accion'] == 'nuevo') {
            if ($this->alta($datos)) {
                $resp = true;
            }
        }
        
        return $resp;
    }
    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Usuario
     */
    private function cargarObjeto($param)
    {
        $obj = null;

        if (
            array_key_exists('idusuario', $param)  and array_key_exists('usnombre', $param) and array_key_exists('uspass', $param)
            and array_key_exists('usmail', $param) and array_key_exists('usdeshabilitado', $param)
        ) {
            $obj = new Usuario();
            $obj->cargar($param['idusuario'], $param['usnombre'], $param['uspass'], $param['usmail'], $param['usdeshabilitado']);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Usuario
     */

    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idusuario'])) {
            $obj = new Usuario();
            $obj->cargar($param['idusuario'], null, null, null, null);
        }
        return $obj;
    }


    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idusuario']))
            $resp = true;
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        $param['idusuario'] = null;
        $elObjtTabla = $this->cargarObjeto($param);
        if ($elObjtTabla != null and $elObjtTabla->insertar()) {
            $resp = true;
        }
        return $resp;
    }



    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjetoConClave($param);
            if ($elObjtTabla != null and $elObjtTabla->eliminar()) {
                $resp = true;
            }
        }

        return $resp;
    }

    /**
     * permite modificar un objeto
     * @param array $param
     * @return boolean
     */
    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjeto($param);
            if ($elObjtTabla != null and $elObjtTabla->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }


    /**
     * permite buscar un objeto
     * @param array $param
     * @return array
     */
    public function buscar($param)
    {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idusuario']))
                $where .= " and idusuario =" . $param['idusuario'];
            if (isset($param['usnombre']))
                $where .= " and usnombre ='" . $param['usnombre'] . "'";
            if (isset($param['usmail']))
                $where .= " and usmail ='" . $param['usmail'] . "'";
            if (isset($param['uspass']))
                $where .= " and uspass ='" . $param['uspass'] . "'";
            if (isset($param['usdeshabilitado']))
                $where .= " and usdeshabilitado is null";
        }
        $obj = new Usuario();
        $arreglo = $obj->listar($where);
        return $arreglo;
    }

   


    /*public function cargarComprasUser()
    {
        $session = new Session();
        $salida = [];
        $compra = new abmCompra();

        if ($session->validar()) {
            $usuario = $session->getUsuario();
            $arr['idusuario'] =  $usuario->getIdUsuario();
            $salida =  $compra->buscar($arr);
        }
        return $salida;
    }

    /**Devuelve un arreglo de compras que tienen estadotipo 0 
     * y tienen fecha fin null de ese estado, (encarrito) */
    /*public function cargarCarritoUser()
    {

        $session = new Session();
        $idUser  = $session->getUsuario()->getIdUsuario();
        $estado_en_carrito = [];
        /*Obtengo desde la bd mediante una consulta las 
        compras que tienen estadotipo 0 y 
        tienen fecha fin null de ese estado
        y tienen idusuario actual*/
        /*$bd = new BaseDatos();
        $sql = "SELECT * FROM compra c INNER JOIN compraestado ce ON c.idcompra = ce.idcompra WHERE ce.idcompraestadotipo = 0 AND ce.cefechafin IS NULL AND c.idusuario = " . $idUser;
        $res = $bd->Ejecutar($sql);
        if ($res > -1) {
            if ($res > 0) {
                while ($row = $bd->Registro()) {
                    $obj = new Compra();
                    $obj->setIdCompra($row['idcompra']);
                    $obj->buscar();
                    array_push($estado_en_carrito, $obj);
                }
            }
        }
        return $estado_en_carrito;
    }*/
}
