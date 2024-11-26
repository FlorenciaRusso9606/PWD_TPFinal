<?php
include_once "../Estructura/header.php";
include_once "../Control/pagPublica.php";
$session = new Session();
$idUsuario = $session->getUsuario();

// Por si se ingresa a través de la URL
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
<div class="ui hidden divider"></div>
<div class="ui center aligned fluid container grid">
    <div class="sixteen wide column">
        <div class="ui center aligned padded segment container grid">
            <div class="ui four wide column">
                <div class="image">
                    <img class="ui massive image" src="./img/Productos/android-chrome-192x192.png" alt="Default-Profile-Picture">
                </div>
            </div>
            <div class="ui ten wide column">
                <?php
                if ($res) {
                    echo "<h1>Información del Usuario</h1>";
                    echo "<p><strong>Nombre de usuario:</strong> <span id='nombreUsuario'>" . $usuario->getUsuarioNombre() . "</span></p>";
                    echo "<p><strong>Email:</strong> <span id='emailUsuario'>" . $usuario->getUsuarioEmail() . "</span></p>";
                    echo "<p><strong>Contraseña:</strong> <span id='passUsuario'> " . str_repeat('*', strlen($usuario->getUsuarioPassword())) . "</p>";

                    // Botón Editar
                    echo '<button class="ui button primary" onclick="abrirFormularioEdicion(' . $usuario->getUsuarioId() . ')">Editar</button>';
                } else {
                    echo "<h1>No se encontró el usuario</h1>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Formulario de edición -->
<div id="formularioEdicionUsuario" class="ui modal">
    <div class="ui padded segment">
        <h5 class="header">Editar Usuario</h5>
        <form id="formEditarUsuario" class="ui form">
            <input type="hidden" name="idusuario" id="idUsuarioInput">
            <div class="field">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="usnombre">
            </div>
            <div class="field">
                <label for="email">Email</label>
                <input type="email" id="email" name="usmail">
            </div>
            <div class="field">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="uspass">
            </div>
            <button type="submit" class="ui button primary" style="margin-top: 20px;">Guardar</button>
            <button type="button" class="ui button secondary" onclick="cerrarFormularioEdicion()">Cerrar</button>
        </form>
    </div>
</div>

<!-- Modal para mensajes -->
<div id="mensajeModal" class="ui modal">
    <div class="header" id="mensajeModalHeader"></div>
    <div class="content">
        <p id="mensajeModalContent"></p>
    </div>
    <div class="actions">
        <div class="ui approve button">OK</div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.js"></script>
<script>
    function hashPassword() {
        var pass = document.getElementById('password').value;
        pass = CryptoJS.MD5(pass).toString();
        document.getElementById('password').value = pass;
    }

    function abrirFormularioEdicion(idUsuario) {
        $('#idUsuarioInput').val(idUsuario);
        $('#nombre').val($('#nombreUsuario').text());
        $('#email').val($('#emailUsuario').text());
        $('#formularioEdicionUsuario').modal("show");
    }

    function cerrarFormularioEdicion() {
        $('#formularioEdicionUsuario').modal("hide");
    }

    function mostrarMensaje(tipo, mensaje) {
        const header = tipo === "success" ? "Éxito" : "Error";
        $('#mensajeModalHeader').text(header);
        $('#mensajeModalContent').html(mensaje); // Se usa .html() para procesar etiquetas como <br>
        $('#mensajeModal').modal('show');
    }

    function validarFormulario() {
        const nombre = $('#nombre').val().trim();
        const email = $('#email').val().trim();
        const password = $('#password').val().trim();

        let mensajeError = '';
        if (!nombre) mensajeError += 'El nombre no puede estar vacío.<br>';
        if (!validarEmail(email)) mensajeError += 'El email no es válido.<br>';
        if (!password) mensajeError += 'La contraseña no puede estar vacía.<br>';

        if (mensajeError) {
            mostrarMensaje("error", mensajeError);
            return false;
        }
        return true;
    }

    function validarEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }

    $(document).ready(function() {
        $('#formEditarUsuario').on('submit', function(event) {
            event.preventDefault();

            if (!validarFormulario()) return;

            hashPassword();

            const datosFormulario = $(this).serialize();

            $.ajax({
                url: './Accion/accionEditarUsuario.php',
                type: 'POST',
                data: datosFormulario,
                success: function(data) {
                    let result;
                    try {
                        result = JSON.parse(data);
                    } catch (e) {
                        mostrarMensaje("error", "Ocurrió un error al intentar actualizar el usuario.");
                        return;
                    }

                    if (result.respuesta) {
                        $('#nombreUsuario').text(result.usnombre);
                        $('#emailUsuario').text(result.usmail);
                        $('#passUsuario').text('*'.repeat(result.uspassLength));

                        cerrarFormularioEdicion();
                        mostrarMensaje("success", "Usuario actualizado correctamente.");
                    } else {
                        mostrarMensaje("error", result.errorMsg);
                    }
                },
                error: function() {
                    mostrarMensaje("error", "Error al guardar los datos del usuario.");
                }
            });
        });
    });
</script>

<?php include_once "../Estructura/footer.php"; ?>
