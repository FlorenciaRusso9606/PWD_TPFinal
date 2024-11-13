<?php

include_once "../configuracion.php";
$data = data_submitted();

$mensaje = null;
if (isset($data["error"])) {
    $mensaje = "<p>Usuario o contraseña incorrectos</p>";
}


?>

<!DOCTYPE html>
<?php include_once "../Estructura/header.php";?>
    <div class="ui center aligned fluid container grid">

        <div class="ui hidden divider sixteen wide column"></div>
        <div class="ui center aligned very padded container eight wide column">

            <div class="ui raised segment">
                <h1>Login</h1>

                <?php

                if ($mensaje != null) {
                    echo $mensaje;
                }

                ?>

                <form class="ui form" action="Accion/verificarLogin.php" method="post" onsubmit="encriptarPassword()">
                    <div class="field">
                        <label for="username">Usuario:</label>
                        <input type="text" id="username" name="usnombre" required>
                    </div>
                    <div class="field">
                        <label for="password">Contraseña:</label>
                        <input type="password" id="password" name="uspass_visible" required>
                        <input type="hidden" id="hashedPassword" name="uspass">
                    </div>
                    <button class="ui button" type="submit">Submit</button>
                </form>

            </div>


        </div>

    </div>

<script>
        function encriptarPassword() {
            var passwordField = document.getElementById('password');
            var hashedPasswordField = document.getElementById('hashedPassword');
            hashedPasswordField.value = hex_md5(passwordField.value);
            passwordField.value = ''; // Clear the visible password field
        }
    </script>
    <?php include_once "../Estructura/footer.php";?>