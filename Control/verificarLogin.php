<?php

class VerificarLogin
{

    public function verificarLogin($data)
    {
        $res = false;
        $abmUsuario = new AbmUsuario();
        $usuario = $abmUsuario->buscar($data);

        if (count($usuario) > 0) {
            //Usuario encontrado
            $session = new Session();
            $session->iniciar($usuario[0]->getUsuarioNombre(), $usuario[0]->getUsuarioPassword());
            $res = true;
        } else {
            //Usuario no encontrado
            $res = false;
        }
        return $res;
    }
}
