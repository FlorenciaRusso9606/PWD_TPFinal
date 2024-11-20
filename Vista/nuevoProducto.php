<?php
include_once "../configuracion.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once "../Estructura/header.php"; ?>

<body>
    <div class="container mt-5">
        <?php
        $sesion = new Session;
        $controlProducto = new ControlProducto;
        if (isset($data['idproducto'])) {
            $producto = $control->productoActual($data);
        }
        if ($sesion->getRol() == 2) { ?>
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0 text-center">
                        <?php echo isset($producto) ? 'Editar Producto' : 'Agregar Producto'; ?>
                    </h2>
                </div>
                <div class="card-body">
                    <form id="productoForm" action="accion/accionNuevoProducto.php" method="post" class="needs-validation" novalidate>
                        <div id="mensaje" class="alert alert-danger d-none"></div>
                        <input name="idproducto" id="idproducto" class="form-control" type="hidden"
                            value="<?php echo isset($producto) ? $producto->getIdProducto() : null; ?>">

                        <div class="mb-3">
                            <label for="pronombre" class="form-label">Nombre:</label>
                            <input type="text" name="pronombre" id="pronombre" class="form-control"
                                value="<?php echo isset($producto) ? $producto->getProNombre() : ''; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="prodetalle" class="form-label">Detalle:</label>
                            <textarea name="prodetalle" id="prodetalle" class="form-control" rows="3" required><?php echo isset($producto) ? $producto->getProDetalle() : ''; ?></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="procantstock" class="form-label">Cantidad:</label>
                                <input type="number" name="procantstock" id="procantstock" class="form-control"
                                    value="<?php echo isset($producto) ? $producto->getProCantStock() : ''; ?>" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="proprecio" class="form-label">Precio:</label>
                                <input type="number" name="proprecio" id="proprecio" class="form-control" step="0.01"
                                    value="<?php echo isset($producto) ? $producto->getProPrecio() : ''; ?>" required>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg">
                                <?php echo isset($producto) ? 'Guardar Cambios' : 'Agregar Producto'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php } ?>
    </div>

    <script>
        $(document).ready(function() {
            $('#productoForm').on('submit', function(e) {
                e.preventDefault();

                // Limpiar mensajes previos
                $('#mensaje').removeClass('d-none').html('');

                // Obtener los valores de los campos
                const nombre = $('#pronombre').val().trim();
                const detalle = $('#prodetalle').val().trim();
                const cantidad = $('#procantstock').val().trim();
                const precio = $('#proprecio').val().trim();

                // Validar los campos
                let errores = [];

                if (!nombre) {
                    errores.push('El campo "Nombre" es obligatorio.');
                }

                if (!detalle) {
                    errores.push('El campo "Detalle" es obligatorio.');
                }

                if (!cantidad || isNaN(cantidad) || parseInt(cantidad) <= 0) {
                    errores.push('El campo "Cantidad" debe ser un número mayor a 0.');
                }

                if (!precio || isNaN(precio) || parseFloat(precio) <= 0) {
                    errores.push('El campo "Precio" debe ser un número mayor a 0.');
                }

                // Mostrar errores si existen
                if (errores.length > 0) {
                    let mensajeErrores = '<ul>';
                    errores.forEach(function(error) {
                        mensajeErrores += `<li>${error}</li>`;
                    });
                    mensajeErrores += '</ul>';
                    $('#mensaje').removeClass('d-none').html(mensajeErrores);
                    return; // Detener el envío
                }

                // Si todo está correcto, enviar el formulario por AJAX
                $.ajax({
                    url: 'Accion/accionNuevoProducto.php',
                    type: 'POST',
                    dataType: 'json', // Aseguramos que jQuery trate la respuesta como JSON
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            window.location.href = "index.php"; // Redirige al éxito
                        } else {
                            $('#mensaje').removeClass('d-none').html('<p>' + response.message + '</p>'); // Muestra mensaje de error
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);
                        $('#mensaje').removeClass('d-none').html('<p>Hubo un error al procesar la solicitud.</p>');
                    }
                });

            });
        });
    </script>
</body>

</html>