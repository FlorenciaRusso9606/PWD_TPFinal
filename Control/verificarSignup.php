<?php
class VerificarSignup
{
	public function verificarSignup($datos)
	{
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
			if (count($arregloObjUsers) == 0 || $arregloObjUsers == null) {
				$idNuevoUser = 1;
			}
			$idNuevoUser = count($arregloObjUsers) + 1;
			/* var_dump($idNuevoUser); */ //4
			$nuevoUsuario = new Usuario();
			$nuevoUsuario->setear($idNuevoUser, $datos['usnombre'], $datos['uspass'], $datos['usmail'], date('Y-m-d H:i:s'));
			$nuevoUsuario->insertar();
			/* $nuevoUsuario->setUsuarioDeshabilitado(NULL); */ //no lo setea, sigue con la fecha
			/* var_dump($nuevoUsuario); */
			$objUserRol = new AbmUsuarioRol();
			$param = ['idusuario' => $idNuevoUser, 'idrol' => 3];
			$objUserRol->alta($param);
			/* var_dump($objUserRol); */
			$resp = true;
		}
		return $resp;
	}
}
