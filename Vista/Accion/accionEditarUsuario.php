<?php
include_once("../estructura/cabeceraBT.php");
$datos = data_submitted();
$resp = false;
$objTrans = new ABMUsuario();
if (isset($datos['accion'])){
    if (isset($datos['accion'])){
        $resp = $objTrans->abm($datos);
        if($resp){
            $mensaje = '<div class="container text-center" id="cardBienvenida">
            <div class="card shadow-lg p-4" style="max-width: 450px; margin: auto; border-radius: 15px;">
                <div class="card-body">
                    <h2 class="card-title mb-3">Se realizó la acción '.$datos['accion'].'</b></h2>
                    <a href="../listaMascotas.php" class="btn btn-danger btn-lg mb-2">Volver a la Lista</a>
                </div>
            </div>
          </div>';
        }else {
            $mensaje = '<div class="container text-center" id="cardBienvenida">
            <div class="card shadow-lg p-4" style="max-width: 450px; margin: auto; border-radius: 15px;">
                <div class="card-body">
                    <h2 class="card-title mb-3">La acción '.$datos['accion'].' no pudo concretarse</b></h2>
                    <a href="../listaMascotas.php" class="btn btn-danger btn-lg mb-2">Volver a la Lista</a>
                </div>
            </div>
          </div>';
        }
    }
    
   
}
?>
<!DOCTYPE html>
<?php include_once "../../Estructura/header.php"; ?>

<div class="container mt-5 text-center">

<?php

    echo $mensaje;
?>
    
</div>
<?php include_once "../../Estructura/footer.php"; ?>

</html>
