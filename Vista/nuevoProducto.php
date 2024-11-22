<?php
include_once "../Control/pagPublica.php";
include_once "../configuracion.php";
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once "../Estructura/header.php";
if ($rol !== 2) {
    header("Location: index.php");
    exit();
}
?>


<div class="ui hidden divider"></div>
<div class="ui container grid center aligned segment">

    <?php
    $data = data_submitted();
    $sesion = new Session;
    $controlProducto = new ControlProducto;
    if (isset($data['idproducto'])) {
        $producto = $controlProducto->productoActual($data);
    }
    if ($sesion->getRol() == 2) { ?>
        <div class="ui ten wide column ">
            <div class="ui header">
                <h2 class=" center aligned">
                    <?php echo isset($producto) ? 'Editar Producto' : 'Agregar Producto'; ?>
                </h2>
            </div>
            <div class="basic segment grid">
                <form id="productoForm" method="post" class="ui form" novalidate>
                    <div id="mensaje" class="ui red message hidden"></div>
                    <input name="idproducto" id="idproducto" class="required field" type="hidden"
                        value="<?php echo isset($producto) ? $producto->getIdProducto() : null; ?>">

                    <div class="ten wide column">
                        <label for="pronombre" class="form-label">Nombre:</label>
                        <input type="text" name="pronombre" id="pronombre" class="required field"
                            value="<?php echo isset($producto) ? $producto->getProNombre() : ''; ?>" required>
                    </div>

                    <div class="ten wide column">
                        <label for="prodetalle" class="form-label">Detalle:</label>
                        <textarea name="prodetalle" id="prodetalle" class="required field" rows="3" required><?php echo isset($producto) ? $producto->getProDetalle() : ''; ?></textarea>
                    </div>

                    <div class="ui grid center aligned ">
                        <div class="eight wide column">
                            <label for="procantstock" class="form-label">Cantidad:</label>
                            <input type="number" name="procantstock" id="procantstock" class="form-control"
                                value="<?php echo isset($producto) ? $producto->getProCantStock() : ''; ?>" required>
                        </div>

                        <div class="eight wide column">
                            <label for="proprecio" class="form-label">Precio:</label>
                            <input type="number" name="proprecio" id="proprecio" class="form-control" step="0.01"
                                value="<?php echo isset($producto) ? $producto->getProPrecio() : ''; ?>" required>
                        </div>
                    </div>
                    <div class="ui hidden divider"></div>
                    <div class="basic padded segment">
                        <button type="submit" class="ui primary button">
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

            if (!cantidad || isNaN(cantidad) || parseInt(cantidad) < 0) {
                errores.push('El campo "Cantidad" no puede ser negtivo.');
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
                $('#mensaje').removeClass('hidden').addClass('red').html(mensajeErrores);
                return; // Detener el envío
            }

            // Si todo está correcto, enviar el formulario por AJAX
            $.ajax({
                url: 'Accion/accionNuevoProducto.php',
                type: 'POST',
                dataType: 'json', // Aseguramos que jQuery trate la respuesta como JSON
                data: $(this).serialize(),
                success: function(response) {
                    console.log('Response:', response); // Depuración: imprime la respuesta en la consola

                    if (response.success) {
                        $('#mensaje').removeClass('red').addClass('green').removeClass('hidden').html('<p>' + response.message + '</p>');
                    } else {
                        $('#mensaje').removeClass('hidden').addClass('red').html('<p>' + response.message + '</p>'); // Muestra mensaje de error
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                    console.error('Response:', xhr.responseText);
                    $('#mensaje').removeClass('hidden').addClass('red').html('<p>Hubo un error al procesar la solicitud.</p>');
                }
            });

        });
    });
</script>

<?php include_once "../Estructura/footer.php";
