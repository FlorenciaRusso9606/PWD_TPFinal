<?php

class ControlTablaMenu
{
    /**
     * Da de alta un menu y devuelve la respuesta
     * @param mixed $param
     * @return bool
     */
    public function altaMenu($param)
    {
        $objMenu = new AbmMenu();
        $respuesta = $objMenu->alta($param);

        $result = $objMenu->buscar(["menombre" => $param["menombre"], "medescripcion" => $param["medescripcion"]]);

        if (is_array($result) && count($result) > 0) {
            $idMenu = $result[0]->getIdmenu();
            $idRol = $result[0]->getObjMenuPadre() ? $result[0]->getObjMenuPadre()->getIdmenu() : null;

            if ($idRol !== null) {
                $paramMenuRol = [
                    "idmenu" => $idMenu,
                    "idrol" => $idRol
                ];
                $objMenuRol = new AbmMenuRol();
                $objMenuRol->alta($paramMenuRol);
            }
        }

        return $respuesta;
    }


    /**
     * Elimina un menu y su relación en menuRol
     * @param mixed $param
     * @return bool
     */
    public function bajaMenu($param)
    {
        $objMenu = new AbmMenu();
        $objMenuRol = new AbmMenuRol();
        $respuesta = false;

        // Eliminar la relación en menuRol primero
        $paramMenuRol = ["idmenu" => $param["idmenu"]];
        $objMenuRol->baja($paramMenuRol);

        // Luego eliminar el menú
        if ($objMenu->baja($param)) {
            $respuesta = true;
        }

        return $respuesta;
    }

    /**
     * Modifica un menu y su relación en menuRol
     * @param mixed $param
     * @return bool
     */
    public function modificarMenu($param)
    {
        $objMenu = new AbmMenu();
        $objMenuRol = new AbmMenuRol();
        $respuesta = false;

        if ($objMenu->modificacion($param)) {
            $paramMenuRol = ["idmenu" => $param["idmenu"]];
            $objMenuRol->baja($paramMenuRol);

            $result = $objMenu->buscar(["idmenu" => $param["idmenu"]]);
            if (is_array($result) && count($result) > 0) {
                $idMenu = $result[0]->getIdmenu();
                $idRol = $result[0]->getObjMenuPadre()->getIdmenu();

                if ($idRol !== null) {
                    $paramMenuRol = [
                        "idmenu" => $idMenu,
                        "idrol" => $idRol
                    ];
                    $objMenuRol->alta($paramMenuRol);
                }
            }

            $respuesta = true;
        }

        return $respuesta;
    }


    public function listarMenu($data)
    {
        $arreglo = [];
        $abmMenu = new AbmMenu();
        $list = $abmMenu->buscar($data);
        $arreglo = array();
        foreach ($list as $elem) {
            $nuevoElem['idmenu'] = $elem->getIdMenu();
            $nuevoElem["menombre"] = $elem->getMenombre();
            $nuevoElem["medescripcion"] = $elem->getMedescripcion();
            $nuevoElem["idpadre"] = $elem->getObjMenuPadre();
            if ($elem->getObjMenuPadre() != null) {
                $nuevoElem["idpadre"] = $elem->getObjMenuPadre()->getMeNombre();
            }
            $nuevoElem["medeshabilitado"] = $elem->getMedeshabilitado();
            array_push($arreglo, $nuevoElem);
        }
        return $arreglo;
    }
}
