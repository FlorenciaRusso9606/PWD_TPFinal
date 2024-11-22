<?php

include_once "../configuracion.php";
$data = data_submitted();

$mensaje = null;
if (isset($data["error"])) {
    $mensaje = "<p>Usuario o contraseña incorrectos</p>";
}


?>

<!DOCTYPE html>
<style>
    body {
        display: grid;
        min-height: 100dvh;
        grid-template-rows: auto 1fr auto;
        background-color: azure;
    }
</style>
<?php include_once "../Estructura/header.php"; ?>
<div class="ui center aligned fluid container grid">

    <div class="ui hidden divider sixteen wide column"></div>
    <div class="ui center aligned very padded container eight wide column">

        <div class="ui raised segment">
            <h1>Login</h1>

            <?php

            /*   if ($mensaje != null) {
                echo $mensaje;
            } */

            ?>
            <div id="mensaje" class="ui red message hidden"></div>

            <form class="ui form" id="loginForm" method="post">
                <div class="field">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="usnombre">
                </div>
                <div class="field">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="uspass_visible">
                    <input type="hidden" id="hashedPassword" name="uspass">
                </div>
                <button class="ui button" type="submit">Submit</button>
            </form>

        </div>


    </div>

</div>

<script>
    $(document).ready(function() {
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            const password = $('#password').val();
            const hashedPassword = CryptoJS.MD5(password).toString();

            $('#hashedPassword').val(hashedPassword);
            $('#password').val('');

            $.ajax({
                url: 'Accion/verificarLogin.php',
                type: 'POST',
                dataType: 'json', // Asegura que la respuesta se trate como JSON
                data: $(this).serialize(),
                success: function(response) {
                    console.log('Respuesta AJAX:', response); // Depuración
                    if (response.success) {
                        // Login exitoso, redirigir al usuario
                        window.location.href = "index.php";
                    } else {
                        // Mostrar el mensaje de error
                        $('#mensaje').html('<p>' + response.message + '</p>');
                        $('#mensaje').removeClass('hidden');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Manejar errores de la solicitud AJAX
                    $('#mensaje').html('<p>Error al procesar la solicitud. Inténtalo de nuevo.</p>');
                    $('#mensaje').removeClass('hidden');
                    console.error('Error AJAX:', textStatus, errorThrown);
                }
            });
        });
    });
</script>
<?php include_once "../Estructura/footer.php"; ?>