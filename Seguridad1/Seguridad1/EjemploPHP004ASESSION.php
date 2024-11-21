<?php
session_start();
/**
Se muestra el valor actual del la variable Test de la session
*/
echo 'Valor actual='.$_SESSION["Test"];
$_SESSION["Test"]='Valor';
?> 
