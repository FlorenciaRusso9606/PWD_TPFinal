$(document).ready(function () {
    $('#loginForm').on('submit', function (e) {
        e.preventDefault(); // Evita que el formulario se envíe de forma tradicional

        // Encriptar la contraseña
        var password = $('#password').val();
        var hashedPassword = CryptoJS.MD5(password).toString();
        $('#hashedPassword').val(hashedPassword);
        $('#password').val(''); // Limpia el campo visible de la contraseña

        // Preparar los datos del formulario
        var formData = $(this).serialize();

        // Enviar los datos mediante AJAX
        $.ajax({
            url: 'Accion/verificarLogin.php', // Ruta al archivo PHP que procesa el login
            type: 'POST',
            data: formData,
            success: function (response) {
                // Manejar la respuesta del servidor
                $('#mensaje').html(response);
            },
            error: function (xhr, status, error) {
                // Manejar errores
                $('#mensaje').html('<p>Error al procesar el login. Por favor, inténtelo de nuevo.</p>');
            }
        });
    });
});