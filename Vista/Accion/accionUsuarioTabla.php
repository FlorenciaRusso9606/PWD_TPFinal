<?php

include_once "../../configuracion.php";
include_once "../../Control/pagPublica.php";  
$data = data_submitted();
$respuesta = false;
$mensaje = "";
$abmUsuario = new ABMUsuario();
$abmUsuarioRol = new abmUsuarioRol();

if (isset($data['accion'])) {
    $accion = $data['accion'];

    switch ($accion) {
        case 'alta':
            if (isset($data['usnombre']) && isset($data['usmail']) && isset($data['uspass']) && isset($data['idrol'])) {
                // Hashea la contraseña con md5
                $data['uspass'] = md5($data['uspass']);
                error_log(print_r($data, true)); // Verifica los datos enviados
                $respuestaUsuario = $abmUsuario->alta($data);
                if ($respuestaUsuario) {
                    $data['idusuario'] = $abmUsuario->buscar(['usnombre' => $data['usnombre'], 'usmail' => $data['usmail']])[0]->getUsuarioId();
                    $respuesta = $abmUsuarioRol->alta($data);
                    if (!$respuesta) {
                        $mensaje = "No se pudo asignar el rol al usuario";
                    }
                } else {
                    $mensaje = "No se pudo dar de alta el usuario: ";
                }
            } else {
                $mensaje = "Datos incompletos para dar de alta el usuario";
            }
            break;

        case 'mod':
            if (isset($data['idusuario']) && isset($data['usnombre']) && isset($data['usmail']) && isset($data['uspass']) && isset($data['idrol'])) {
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
                            if (!$respuesta) {
                                $mensaje = "No se pudo asignar el nuevo rol al usuario";
                            }
                        } else {
                            $mensaje = "No se pudo eliminar el rol actual del usuario";
                        }
                    } else {
                        // Si no hay un rol actual, simplemente asignar el nuevo rol
                        $respuesta = $abmUsuarioRol->alta($data);
                        if (!$respuesta) {
                            $mensaje = "No se pudo asignar el nuevo rol al usuario";
                        }
                    }
                } else {
                    $mensaje = "La acción MODIFICACIÓN no pudo concretarse";
                }
            } else {
                $mensaje = "Datos incompletos para modificar el usuario";
            }
            break;

        case 'baja':
            if (isset($data['idusuario'])) {
                $respuestaUsuarioRol = $abmUsuarioRol->baja(['idusuario' => $data['idusuario'], 'idrol' => $data['idrol']]);
                if ($respuestaUsuarioRol) {
                    $respuesta = $abmUsuario->baja($data);
                    if (!$respuesta) {
                        $mensaje = "No se pudo eliminar el usuario";
                    }
                } else {
                    $mensaje = "No se pudo eliminar el rol del usuario";
                }
            } else {
                $mensaje = "Datos incompletos para eliminar el usuario";
            }
            break;

        case 'listar':
            $list = $abmUsuario->buscar($data);
            $arreglo_salida = array();
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
                array_push($arreglo_salida, $nuevoElem);
            }
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
