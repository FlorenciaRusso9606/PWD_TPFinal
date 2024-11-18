<?php

include_once "../configuracion.php";

$session = new Session();
$idUsuario = $session->getUsuario();

//por si se ingresa a través de la url
if ($idUsuario == null) {
    header("Location: " . $PRINCIPAL);
}



$res = false;
if ($idUsuario) {
    $abmUsuario = new AbmUsuario();
    $usuario = $abmUsuario->buscar(['idusuario' => $idUsuario]);

    if (count($usuario) > 0) {
        $usuario = $usuario[0];
        $res = true;
    }
}


?>

<!DOCTYPE html>
<?php include_once "../Estructura/header.php"; ?>

<div class="ui  container">

    <div class="sixteen wide column">
        <div class="ui center aligned padded segment container grid">
            <div class="ui four wide column">
                <div class="image">
                    <img class="ui massive image" src="Assets/img/image.png" alt="Defualt-Profile-Picture">
                </div>
            </div>
            <div class="ui ten wide column">
                <?php

                if ($res == true) {
                    echo "<h1>Información del Usuario</h1>";
                    echo "<p><strong>Nombre de usuario:</strong> " . $usuario->getUsuarioNombre() . "</p>";
                    echo "<p><strong>Email:</strong> " . $usuario->getUsuarioEmail() . "</p>";
                    echo "<p><strong>Contraseña:</strong> " . str_repeat('*', strlen($usuario->getUsuarioPassword())) . "</p>";
                } else {
                    echo "<h1>No se encontró el usuario</h1>";
                }
                ?>
            </div>

        </div>
    </div>
</div>

<?php include_once "../Estructura/footer.php"; ?>