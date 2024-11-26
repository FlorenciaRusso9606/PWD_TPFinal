<?php
class VerificarSignup
{
public function verificarSignup($datos)
	{
		if (empty($datos['usmail']) || empty($datos['usnombre']) || empty($datos['uspass'])) {
			$resp = false; // Indica que hay campos vacíos
		} else {
			$objUsuario = new AbmUsuario();
			$arregloObjUsers = $objUsuario->buscar(null);
			/* var_dump($arregloObjUsers); */

			if (isset($arregloObjUsers)) {
				$seEncontro = false;
				$i = 0;
				while ($i < count($arregloObjUsers) && !$seEncontro) {
					if ($arregloObjUsers[$i]->getUsuarioEmail() == $datos['usmail']) {
						$seEncontro = true;
						/* echo "ya existe el mail"; */
						$resp = false;
					}
					$i++;
				}
			}
			if (!$seEncontro) {


				/* var_dump($idNuevoUser); */ //4
				$nuevoUsuario = new ABMUsuario();

				$paramUsuario = ['usnombre' => $datos['usnombre'], 'uspass' => $datos['uspass'], 'usmail' => $datos['usmail'], "usdeshabilitado" => date("Y-m-d H:i:s")];


				if ($nuevoUsuario->alta($paramUsuario)) {
					$nuevoUser = $nuevoUsuario->buscar($paramUsuario);
					if (isset($nuevoUser[0])) {
						$idNuevoUser = $nuevoUser[0]->getUsuarioId();

						/* $nuevoUsuario->setUsuarioDeshabilitado(NULL); */ //no lo setea, sigue con la fecha
						/* var_dump($nuevoUsuario); */
						$objUserRol = new AbmUsuarioRol();

						$param = ['idusuario' => $idNuevoUser, 'idrol' => 3];

						$objUserRol->alta($param);

						/* var_dump($objUserRol); */
						$session = new Session();
						$session->iniciar($nuevoUser[0]->getUsuarioNombre(), $nuevoUser[0]->getUsuarioPassword());
						$resp = true;
					} else {
						$resp = false;
					}
				} else {
					$resp = false;
				}
			}
		}
		return $resp;
	}
}
