<?php
include_once '../estructura/header.php';

if (isset($_GET["id"])) {
	/* var_dump($_GET["id"]); */
	$objCarrito = new Carrito();
	$param["idproducto"] = $_GET["id"];
	$resp = $objCarrito->eliminarProducto($param);
	header('Location: ../Vista/carritoCompra.php');
	exit;
}
