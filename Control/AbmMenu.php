<?php
class AbmMenu {
    //Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto


    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto
     * @param array $param
     * @return Menu
     */
    private function cargarObjeto($param) {
        $obj = null;

        if (array_key_exists('idmenu', $param) and array_key_exists('menombre', $param)) {
            $obj = new Menu();
            $objmenu = null;
            if (isset($param['idpadre'])) {
                $objmenu = new Menu();
                $objmenu->setIdmenu($param['idpadre']);
                $objmenu->cargar();
            }
            if (!isset($param['medeshabilitado'])) {
                $param['medeshabilitado'] = null;
            } else {
                $param['medeshabilitado'] = date("Y-m-d H:i:s");
            }
            $obj->setear($param['idmenu'], $param['menombre'], $param['medescripcion'], $objmenu, $param['medeshabilitado']);
        }
        return $obj;
    }

    /**
     * Espera como parametro un arreglo asociativo donde las claves coinciden con los nombres de las variables instancias del objeto que son claves
     * @param array $param
     * @return Menu
     */
    private function cargarObjetoConClave($param) {
        $obj = null;

        if (isset($param['idmenu'])) {
            $obj = new Menu();
            $obj->setIdmenu($param['idmenu']);
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
        if (isset($param['idmenu']))
            $resp = true;
        return $resp;
    }

    /**
     * Permite agregar un objeto
     * @param mixed $param
     * @return bool
     */
    public function alta($param) {
        $resp = false;
        $param['idmenu'] = null;
        $param['medeshabilitado'] = null;
        $elObjtTabla = $this->cargarObjeto($param);

        if ($elObjtTabla != null and $elObjtTabla->insertar()) {
            $resp = true;

            // Cargar MenuRol
            $abmMenuRol = new AbmMenuRol();
            if ($elObjtTabla->getObjMenuPadre() != null) {
                $menuPadre = $elObjtTabla->getObjMenuPadre();
                $rolPadre = $abmMenuRol->buscar(['idmenu' => $menuPadre->getIdmenu()]);
                if (!empty($rolPadre)) {
                    $rolPadre = $rolPadre[0];
                    
                    $idRol = $rolPadre->getObjRol()->getIdrol();
                    if (!empty($idRol)) {
                        $abmMenuRol->alta(["idmenu" => $elObjtTabla->getIdmenu(), "idrol" => $idRol]);
                    }
                }
            }
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
    public function modificacion($param) {

        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtMenu = $this->cargarObjeto($param);
            if ($elObjtMenu != null and $elObjtMenu->modificar()) {
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
    public function buscar($param) {
        $where = " true ";
        if ($param <> NULL) {
            if (isset($param['id']))
                $where .= " and id =" . $param['id'];
            if (isset($param['descrip']))
                $where .= " and descrip ='" . $param['descrip'] . "'";
        }
        $arreglo = Menu::listar($where);
        return $arreglo;
    }
}
