<?php
class AbmMenu
{
    private function cargarObjeto($param)
    {
        $obj = null;
        if (array_key_exists('idmenu', $param) && array_key_exists('menombre', $param)) {
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

    private function cargarObjetoConClave($param)
    {
        $obj = null;
        if (isset($param['idmenu'])) {
            $obj = new Menu();
            $obj->setIdmenu($param['idmenu']);
        }
        return $obj;
    }

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idmenu'])) {
            $resp = true;
        }
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        $param['idmenu'] = null;
        $param['medeshabilitado'] = null;
        $elObjtTabla = $this->cargarObjeto($param);
        if ($elObjtTabla != null && $elObjtTabla->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjetoConClave($param);
            if ($elObjtTabla != null && $elObjtTabla->eliminar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtMenu = $this->cargarObjeto($param);
            if ($elObjtMenu != null && $elObjtMenu->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param)
    {
        $where = " true ";
        if ($param != NULL) {
            if (isset($param['idmenu']))
                $where .= " and idmenu =" . $param['idmenu'];
            if (isset($param['menombre']))
                $where .= " and menombre ='" . $param['menombre'] . "'";
            if (isset($param['medescripcion']))
                $where .= " and medescripcion ='" . $param['medescripcion'] . "'";
            if (isset($param['idpadre']))
                $where .= " and idpadre =" . $param['idpadre'];
            if (isset($param['medeshabilitado']) && $param['medeshabilitado'] !== null)
                $where .= " and medeshabilitado ='" . $param['medeshabilitado'] . "'";
            if (isset($param['medeshabilitado']) && $param['medeshabilitado'] === null)
                $where .= " and medeshabilitado is null";
        }

        $arreglo = Menu::listar($where);
        return $arreglo;
    }
}
