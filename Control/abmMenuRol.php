<?php

class abmMenuRol {

    public function abm($datos) {
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
     * @return MenuRol
     */
    private function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idmenu', $param) and array_key_exists('idrol', $param)) {
            $obj = new MenuRol();
            $objMenu = new Menu();
            $objMenu->setIdmenu($param['idmenu']);
            $objMenu->cargar();

            $objRol = new Rol();
            $objRol->setIdrol($param['idrol']);
            $objRol->cargar();

            $obj->setear($objMenu, $objRol);
        }
        return $obj;
    }



    /**
     * Corrobora que dentro del arreglo asociativo estan seteados los campos claves
     * @param array $param
     * @return boolean
     */

    private function seteadosCamposClaves($param) {
        $resp = false;
        if (isset($param['idmenu']) && isset($param['idrol']))
            $resp = true;
        return $resp;
    }

    /**
     * Permite ingresar un registro en la base de datos
     * @param array $param
     * @return boolean
     */
    public function alta($param) {
        $resp = false;
        $obj = $this->cargarObjeto($param);

        if ($obj != null and $obj->insertar()) {
            $resp = true;
        } else {
        }
        return $resp;
    }

    /**
     * permite eliminar un objeto 
     * @param array $param
     * @return boolean
     */
    public function baja($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjeto($param);
            if ($obj != null and $obj->eliminar()) {
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
    public function modificacion($param) {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $obj = $this->cargarObjeto($param);
            if ($obj != null && $obj->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    /**
     * Permite buscar un objeto
     * @param array $param
     * @return array
     */
    public function buscar($param) {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['idmenu']))
                $where .= " and idmenu =" . $param['idmenu'];
            if (isset($param['idrol']))
                $where .= " and idrol =" . $param['idrol'];
        }
        $arreglo = MenuRol::listar($where);
        return $arreglo;
    }
}
