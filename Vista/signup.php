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
<?php include_once "Estructura/header.php"; ?>
<div class="ui center aligned fluid container grid">

	<div class="ui hidden divider sixteen wide column"></div>
	<div class="ui center aligned very padded container eight wide column">

		<div class="ui raised segment">
			<h1>Registrarse</h1>
			<!-- <div id="mensaje" class="ui red message"></div> -->
			<form class="ui form" id="signupForm" name="signupForm" action="./Accion/verificarRegistro.php" method="post">
				<div class="field">
					<label for="username">Usuario:</label>
					<input type="text" id="username" name="usnombre" required>
				</div>
				<div class="field">
					<label for="email">Email:</label>
					<input type="email" id="email" name="usmail" required>
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

			const password = $('#password').val();
			const hashedPassword = CryptoJS.MD5(password).toString();

			$('#hashedPassword').val(hashedPassword);
			$('#password').val('');

			$.ajax({
				url: 'Accion/verificarRegistro.php',
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
					$('#mensaje').html('<p>Error al procesar el signup.</p>');
				}
			});
		});
	});
</script>
<?php include_once "Estructura/footer.php"; ?>