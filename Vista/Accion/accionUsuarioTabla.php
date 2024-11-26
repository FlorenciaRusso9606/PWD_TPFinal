<?php

include_once "../../configuracion.php";
include_once "../../Control/pagPublica.php";
include_once "../../Control/ControlPaginaAccion.php";
$data = data_submitted();
$respuesta = false;
$mensaje = "";
$abmUsuario = new ABMUsuario();
$abmUsuarioRol = new abmUsuarioRol();
$controlUsuario = new ControlTablaUsuario();

if (isset($data['accion'])) {
    $accion = $data['accion'];

    switch ($accion) {
        case 'alta':
            if (isset($data['usnombre']) && isset($data['usmail']) && isset($data['uspass']) && isset($data['idrol'])) {
                $respuesta = $controlUsuario->altaUsuario($data);
                if (!$respuesta) {
                    $mensaje = "Error al dar de alta el usuario";
                }
            } else {
                $mensaje = "Datos incompletos para dar de alta el usuario";
            }
            break;

        case 'mod':
            if (isset($data['idusuario']) && isset($data['usnombre']) && isset($data['usmail']) && isset($data['uspass']) && isset($data['idrol'])) {


                $respuesta = $controlUsuario->modificarUsuario($data);
                if (!$respuesta) {
                    $mensaje = "Error al modificar el usuario";
                }
            } else {
                $mensaje = "Datos incompletos para modificar el usuario";
            }
            break;

        case 'baja':
            if (isset($data['idusuario'])) {
                $respuesta = $controlUsuario->bajaUsuario($data);
                if (!$respuesta) {
                    $mensaje = "Error al eliminar el usuario";
                }
            } else {
                $mensaje = "Datos incompletos para eliminar el usuario";
            }
            break;

        case 'listar':
            $arreglo_salida = $controlUsuario->listarUsuarios($data);
            echo json_encode($arreglo_salida, null, 2);
            exit; // Salir para no ejecutar el resto del script

        default:
            $mensaje = "Acción no válida";
            break;
    }
} else {
    $mensaje = "No se especificó ninguna acción";
}

$retorno['respuesta'] = $respuesta;
if ($mensaje != "") {
    $retorno['errorMsg'] = $mensaje;
}
echo json_encode($retorno);
