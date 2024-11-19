<?php

include_once '../../configuracion.php';
$datos = data_submitted();
var_dump($datos);
$resp = false;
$objModUser = new abmUsuario();
$mensaje = '';
if (isset($datos['idusuario'])) {
    if ($objModUser->modificacion($datos)) {
        
        $resp = true;
        $mensaje = 'La modificación se realizó correctamente';
    } else {
        $mensaje = 'La modificación no pudo concretarse';
    }
} else {
    $mensaje = 'No se pudo obtener el id del usuario';
}
?>

<div class="container mt-5 text-center">

<?php

    echo $mensaje;
?>
    
</div>


