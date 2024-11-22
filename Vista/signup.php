<?php

include_once "../configuracion.php";
include_once "../Control/pagPublica.php";
$data = data_submitted();
$sesion = new Session();
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
			<h1>Registrarse</h1>
			<!-- mensaje de error -->
			<div id="mensaje" class="ui red message hidden"></div>
			<form class="ui form" id="signupForm" name="signupForm" action="./Accion/verificarRegistro.php" method="post">
				<div class="field">
					<label for="username">Usuario:</label>
					<input type="text" id="username" name="usnombre">
				</div>
				<div class="field">
					<label for="email">Email:</label>
					<input type="email" id="email" name="usmail">
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
		$('#signupForm').on('submit', function(e) {
			e.preventDefault();

			const username = $('#username').val().trim();
			const email = $('#email').val().trim();
			const password = $('#password').val().trim();

			// Limpiar mensajes anteriores
			$('#mensaje').html('').addClass('hidden').removeClass('ui red message').removeClass('ui green message');

			// Verificar que ninguno de los campos esté vacío o nulo
			if (username === '' || email === '' || password === '') {
				$('#mensaje').html('<p>Por favor, rellena todos los campos.</p>');
				$('#mensaje').removeClass('hidden').addClass('ui red message');
				return; // Detiene la ejecución si hay campos vacíos
			}

			// Opcional: Validar el formato del email
			const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (!emailRegex.test(email)) {
				$('#mensaje').html('<p>Por favor, introduce un email válido.</p>');
				$('#mensaje').removeClass('hidden').addClass('ui red message');
				return;
			}

			// Si todos los campos están llenos y el email es válido, proceder a hashear la contraseña
			const hashedPassword = CryptoJS.MD5(password).toString();

			$('#hashedPassword').val(hashedPassword);
			$('#password').val('');

			$.ajax({
				url: 'Accion/verificarRegistro.php',
				type: 'POST',
				dataType: 'json', // Indica que se espera una respuesta JSON
				data: $(this).serialize(),
				success: function(response) {
					console.log('Respuesta AJAX:', response); // Para depuración

					if (response.success) {
						// Registro exitoso, redirigir al usuario
						window.location.href = "index.php";
					} else {
						// Mostrar el mensaje de error
						$('#mensaje').html('<p>' + response.message + '</p>');
						$('#mensaje').removeClass('hidden').removeClass('ui green message').addClass('ui red message');
					}
				},
				error: function(jqXHR, textStatus, errorThrown) {
					// Manejar errores de la solicitud AJAX
					$('#mensaje').html('<p>Error al procesar el registro.</p>');
					$('#mensaje').removeClass('hidden').removeClass('ui green message').addClass('ui red message');
					console.error('Error AJAX:', textStatus, errorThrown);
				}
			});
		});
	});
</script>
<?php include_once "../Estructura/footer.php"; ?>