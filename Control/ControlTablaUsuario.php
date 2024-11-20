<?php


class ControlTablaUsuario
{

    public function altaUsuario($data)
    {
        $abmUsuario = new ABMUsuario();
        $abmUsuarioRol = new abmUsuarioRol();
        // Hashea la contraseña con md5
        $data['uspass'] = md5($data['uspass']);
        error_log(print_r($data, true)); // Verifica los datos enviados
        $respuestaUsuario = $abmUsuario->alta($data);
        if ($respuestaUsuario) {
            $data['idusuario'] = $abmUsuario->buscar(['usnombre' => $data['usnombre'], 'usmail' => $data['usmail']])[0]->getUsuarioId();
            $respuesta = $abmUsuarioRol->alta($data);
        }

        return $respuesta;
    }

    public function modificarUsuario($data)
    {

        $abmUsuario = new ABMUsuario();
        $abmUsuarioRol = new abmUsuarioRol();
        // Hashea la contraseña con md5
        $data['uspass'] = md5($data['uspass']);
        // Verificar si se debe habilitar o deshabilitar el usuario

        if (isset($data['usdeshabilitado']) && $data['usdeshabilitado'] == 'true') {
            $data['usdeshabilitado'] = date("Y-m-d H:i:s");
        } else {
            $data['usdeshabilitado'] = null;
        }
        $respuestaUsuario = $abmUsuario->modificacion($data);
        if ($respuestaUsuario) {
            // Buscar el rol actual del usuario
            $rolesActuales = $abmUsuarioRol->buscar(['idusuario' => $data['idusuario']]);
            if (count($rolesActuales) > 0) {
                $rolActual = $rolesActuales[0];
                // Eliminar el rol actual del usuario
                $respuestaBajaRol = $abmUsuarioRol->baja(['idusuario' => $data['idusuario'], 'idrol' => $rolActual->getobjRol()->getidrol()]);
                if ($respuestaBajaRol) {
                    // Asignar el nuevo rol
                    $respuesta = $abmUsuarioRol->alta($data);
                }
            } else {
                // Si no hay un rol actual, simplemente asignar el nuevo rol
                $respuesta = $abmUsuarioRol->alta($data);
            }
        }


        return $respuesta;
    }

    public function bajaUsuario($data)
    {
        $abmUsuario = new ABMUsuario();
        $abmUsuarioRol = new abmUsuarioRol();

        $respuestaUsuarioRol = $abmUsuarioRol->baja(['idusuario' => $data['idusuario'], 'idrol' => $data['idrol']]);
        if ($respuestaUsuarioRol) {
            $respuesta = $abmUsuario->baja($data);
        }

        return $respuesta;
    }

    public function listarUsuarios($data)
    {

        $abmUsuario = new ABMUsuario();
        $abmUsuarioRol = new abmUsuarioRol();
        $arreglo = [];


        $list = $abmUsuario->buscar($data);
        $arreglo = array();
        foreach ($list as $elem) {
            $nuevoElem['idusuario'] = $elem->getUsuarioId();
            $nuevoElem["usnombre"] = $elem->getUsuarioNombre();
            $nuevoElem["usmail"] = $elem->getUsuarioEmail();
            $nuevoElem["usdeshabilitado"] = $elem->getUsuarioDeshabilitado();
            $roles = $abmUsuarioRol->buscar(['idusuario' => $elem->getUsuarioId()]);
            if (count($roles) > 0) {
                $nuevoElem["idrol"] = $roles[0]->getobjRol()->getidrol();
                $nuevoElem["roldescripcion"] = $roles[0]->getobjRol()->getroldescripcion();
            } else {
                $nuevoElem["idrol"] = null;
                $nuevoElem["roldescripcion"] = null;
            }
            array_push($arreglo, $nuevoElem);
        }


        return $arreglo;
    }
}
