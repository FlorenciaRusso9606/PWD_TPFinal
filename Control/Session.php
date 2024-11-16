<?php

class Session {

    // Inicia la sesión
    public function __construct() {
        if (!self::activa()) {
            session_start();
        }
    }

    /**
     * Actualiza las variables de sesión con el ID del usuario
     * @param int $idUsuario
     * @return void
     */
    public function iniciar($nombreUsuario, $psw) {
        $resp = false;
        $obj = new AbmUsuario();

        $param["usnombre"] = $nombreUsuario;
        $param["uspass"] = $psw;
        $param["usdeshabilitado"] = NULL;

        $resultado = $obj->buscar($param);
        if (count($resultado) > 0) {
            $usuario = $resultado[0];
            $_SESSION['idUsuario'] = $usuario->getUsuarioId();
            $resp = true;
        } else {
            $this->cerrar();
        }

        $resultado = $obj->buscar($param);
    }

    /**
     * Valida si la sesión actual tiene un ID de usuario válido
     * @return bool
     */
    public function validar() {
        return isset($_SESSION['idUsuario']);
    }

    /**
     * Verifica que la sesión esté activa
     * @return bool
     */
    public static function activa() {
        return session_status() === PHP_SESSION_ACTIVE;
    }

    /**
     * Devuelve el ID del usuario logueado
     * @return mixed
     */
    public function getUsuario() {
        return $_SESSION['idUsuario'] ?? null;
    }

    
    /**
     * Devuelve el ID de la compra
     * @return mixed
     */
    public function getCompra() {
        return $_SESSION['idUsuario'] ?? null;
    }

 
    public function setCompra($id){
        $_SESSION['idCompra'] =$id;
    }

    /**
     * Devuelve el rol del usuario logueado
     * @return mixed
     */
    public function getRol() {
        $rol = null;
        $idUsuario = $this->getUsuario();
        if ($idUsuario) {
            $abmUsuarioRol = new AbmUsuarioRol();
            $param = ['idusuario' => $idUsuario];
            $usuarioRoles = $abmUsuarioRol->buscar($param);
            if (count($usuarioRoles) > 0) {
                $usuarioRol = $usuarioRoles[0];
                $rol = $usuarioRol->getobjRol()->getidrol();
            }
        }
        return $rol;
    }

    /**
     * Cierra la sesión actual
     * @return void
     */
    public function cerrar() {
        session_unset();
        session_destroy();
    }

    /**
     * 
     */
    public function getCarrito(){
        
        if(!isset($_SESSION['carrito'])){
            $_SESSION['carrito'] = [];
        }
        return $_SESSION['carrito'] ; 
    }
    
    public function setCarrito($array){
        $_SESSION['carrito']= $array;
    }

}
