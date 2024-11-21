<?php
$Titulo = " Gestion de Usuarios";
include_once("../estructura/cabeceraBT.php");
$datos = data_submitted();

$obj= new ABMUsuario();
$lista = $obj->buscar(null);

?>
<!DOCTYPE html>
<html lang="en">
<?php

include_once("Estructura/header.php");
?>


<h3>Usuarios</h3>
<div class="row float-left">
    <div class="col-md-12 float-left">
      <?php 
      if(isset($datos) && isset($datos['msg']) && $datos['msg']!=null) {
        echo $datos['msg'];
      }
     ?>
    </div>
</div>


<div class="row float-right">

    <div class="col-md-12 float-right">
        <a class="btn btn-success" role="button" href="editarUsuario.php?accion=nuevo&id=-1">Nuevo</a>

    </div>
</div>


<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Nombre</th>
            <th scope="col">Correo</th>
            <th scope="col">Desabilitado</th>
            <th scope="col">Acciones</th>
        </tr>
        </thead>
        <tbody>
  
<?php
 if( count($lista)>0){
    foreach ($lista as $objTabla) {
        echo '<tr><td>'.$objTabla->getidusuario().'</td>';
        echo '<td>'.$objTabla->getusnombre().'</td>';
        echo '<td>'.$objTabla->getusmail().'</td>';
        echo '<td>'.$objTabla->getusdeshabilitado().'</td>';
        echo '<td><a class="btn btn-info" role="button" href="editarUsuario.php??accion=editar&idusuario='.$objTabla->getidusuario().'">editar</a>';
        echo '<a class="btn btn-primary" role="button" href="editarUsuario.php?accion=borrar&idusuario='.$objTabla->getidusuario().'">borrar</a></td></tr>';
	}
}

?>
        </tbody>
    </table>
</div>


<?php

include_once("Estructura/footer.php");
?>
