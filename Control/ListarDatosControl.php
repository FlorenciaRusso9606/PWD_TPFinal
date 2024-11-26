<?php

class ListarDatosControl
{
    public function listarUsuarios()
    {
        $abmUsuario = new AbmUsuario;
        $colUsers = $abmUsuario->buscar(null);
        $retorno = array();
        foreach ($colUsers as $elem) {
            $nuevoElem['idusuario'] = $elem->getIdUsuario();
            $nuevoElem['usnombre'] = $elem->getUsNombre();
            $nuevoElem['uspass'] = $elem->getUsPass();
            $nuevoElem['usmail'] = $elem->getUsMail();
            $nuevoElem['usdeshabilitado'] = $elem->getUsDeshabilitado();
            array_push($retorno, $nuevoElem);
        }
        return $retorno;
    }

    public function listarUsRol($data)
    {
        $abm = new AbmUsuarioRol;
        if (isset($data['idusuario'])) {

            $col = $abm->buscar(['idusuario' => $data['idusuario']]);
        } else {
            $col = $abm->buscar(null);
        }
        $retorno = array();
        foreach ($col as $elem) {
            $nuevoElem['idusuario'] = $elem->getObjUsuario()->getIdusuario();
            $nuevoElem['usnombre'] = $elem->getObjUsuario()->getUsNombre();
            $nuevoElem['idrol'] = $elem->getObjRol()->getIdRol();
            $nuevoElem['rodescripcion'] = $elem->getObjRol()->getRoDescripcion();

            array_push($retorno, $nuevoElem);
        }
        return $retorno;
    }

    public function listarRoles()
    {
        $abmRoles = new AbmRol;
        $col = $abmRoles->buscar(null);
        $retorno = array();
        foreach ($col as $elem) {
            $nuevoElem['idrol'] = $elem->getIdRol();
            $nuevoElem['rodescripcion'] = $elem->getRoDescripcion();

            array_push($retorno, $nuevoElem);
        }
        return $retorno;
    }



    public function listarMenuPadre($data)
    {
        $data['idpadre'] = null;
        $abm = new AbmMenu;
        $col = $abm->buscar(null);
        $retorno  = array();
        foreach ($col as $element) {
            if (!$element->getobjmepadre()) {
                $newElelm['idmenu'] = $element->getIdMenu();
                $newElelm['menombre'] = $element->getMeNombre();
                array_push($retorno, $newElelm);
            }
        }
        return $retorno;
    }

    public function listarMenu($data)
    {
        $abmMenu = new AbmMenu;
        $colMenus = $abmMenu->buscar($data);
        $retorno = array();
        foreach ($colMenus as $menu) {
            $newElem['idmenu'] = $menu->getIdMenu();
            $newElem['menombre'] = $menu->getMeNombre();
            $newElem['medescripcion'] = $menu->getMeDescripcion();
            $newElem['idpadre'] = $menu->getObjMePadre();
            if ($newElem['idpadre'] != null) {
                $newElem['idpadre'] = $menu->getObjMePadre()->getMeNombre();
            }
            $newElem['medeshabilitado'] = $menu->getMeDeshabilitado();
            array_push($retorno, $newElem);
        }
        return $retorno;
    }

    public function modMenu($data)
    {
        $resp = false;
        if (isset($data['idmenu'])) {
            if ($data['medeshabilitado'] == 'null') {
                $data['medeshabilitado'] = null;
            }
            $abmMenu = new AbmMenu;
            $data = $abmMenu->buscar(['idpadre' => $data['idpadre']]);
            $resp =  $abmMenu->modificacion($data);
        }
        if (!$resp) {
            $msj = "la modificacion fallo";
        }
        $retorno['respuesta'] = $resp;
        return $retorno;
    }
}
