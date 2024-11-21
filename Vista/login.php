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

            if ($mensaje != null) {
                echo $mensaje;
            }

            ?>
            <div id="mensaje" class="ui red message hidden"></div>



            <form class="ui form" id="loginForm" action="Accion/verificarLogin.php" method="post">
                <div class="field">
                    <label for="username">Usuario:</label>
                    <input type="text" id="username" name="usnombre" required>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                data: $(this).serialize(),
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        window.location.href = "index.php";
                    } else {
                        $('#mensaje').html('<p>' + result.message + '</p>');
                    }
                },
                error: function() {
                    $('#mensaje').html('<p>Error al procesar el login.</p>');
                }
            });
        });
    });
</script>
<?php include_once "../Estructura/footer.php"; ?>