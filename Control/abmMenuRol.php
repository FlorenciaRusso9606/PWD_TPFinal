<?php

class AbmMenuRol
{
    private function cargarObjeto($param)
    {
        $obj = null;
        if (array_key_exists('idmenu', $param) && array_key_exists('idrol', $param)) {
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

    private function seteadosCamposClaves($param)
    {
        $resp = false;
        if (isset($param['idmenu']) && isset($param['idrol'])) {
            $resp = true;
        }
        return $resp;
    }

    public function alta($param)
    {
        $resp = false;
        $elObjtTabla = $this->cargarObjeto($param);
        if ($elObjtTabla != null && $elObjtTabla->insertar()) {
            $resp = true;
        }
        return $resp;
    }

    public function baja($param)
    {
        $resp = false;
        if (isset($param['idmenu'])) {
            $objMenuRol = new MenuRol();
            $objMenuRol->getObjMenu()->setIdmenu($param['idmenu']);
            $resp = $objMenuRol->eliminarPorMenu();
        }
        return $resp;
    }

    public function modificacion($param)
    {
        $resp = false;
        if ($this->seteadosCamposClaves($param)) {
            $elObjtTabla = $this->cargarObjeto($param);
            if ($elObjtTabla != null && $elObjtTabla->modificar()) {
                $resp = true;
            }
        }
        return $resp;
    }

    public function buscar($param)
    {
        $where = " true ";
        if ($param != NULL) {
            if (isset($param['idmenu'])) {
                $where .= " and idmenu =" . $param['idmenu'];
            }
            if (isset($param['idrol'])) {
                $where .= " and idrol =" . $param['idrol'];
            }
        }
        $arreglo = MenuRol::listar($where);
        return $arreglo;
    }
}
