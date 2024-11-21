<?php
include_once "../configuracion.php";
include_once "../Control/pagPublica.php";
$objControl = new AbmProducto();
$List_Producto = $objControl->buscar(null);
?>

<?php include_once "../Estructura/header.php"; ?>

<div class="ui hidden divider"></div>
<div class="ui container grid center aligned">

    <div class="ui sixteen wide column">

        <h2>ABM - Productos</h2>
        <p>Seleccione la acci&oacute;n que desea realizar.</p>

        <div id="messageContainer" class="ui hidden message"></div> <!-- Contenedor para los mensajes -->

        <table class="ui celled table">
            <thead>
                <tr>
                    <th>ID Producto</th>
                    <th>Nombre</th>
                    <th>Detalle</th>
                    <th>Cantidad en Stock</th>
                    <th>Precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="productosTableBody">
                <?php foreach ($List_Producto as $producto) : ?>
                    <tr data-id="<?php echo $producto->getIdProducto(); ?>">
                        <td data-field="idproducto"><?php echo $producto->getIdProducto(); ?></td>
                        <td data-field="pronombre"><?php echo $producto->getProNombre(); ?></td>
                        <td data-field="prodetalle"><?php echo $producto->getProDetalle(); ?></td>
                        <td data-field="procantstock"><?php echo $producto->getProCantStock(); ?></td>
                        <td data-field="proprecio"><?php echo $producto->getProPrecio(); ?></td>
                        <td>
                            <button class="ui button" onclick="editProducto(<?php echo $producto->getIdProducto(); ?>)">Editar</button>
                            <button class="ui button" onclick="confirmDestroyProducto(<?php echo $producto->getIdProducto(); ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="ui buttons">
            <button class="ui button" onclick="newProducto()">Nuevo Producto</button>
        </div>

        <div id="dlgProductos" class="ui modal">
            <div class="header">Información del Producto</div>
            <div class="content">
                <div id="modalMessageContainer" class="ui hidden message">
                    <!-- Contenedor para los mensajes dentro del modal -->
                </div>
                <form id="fmProductos" class="ui form">
                    <input name="idproducto" id="idproducto" type="hidden">
                    <div class="required field">
                        <label for="pronombre">Nombre:</label>
                        <input name="pronombre" id="pronombre" required>
                    </div>
                    <div class="required field">
                        <label for="prodetalle">Detalle:</label>
                        <input name="prodetalle" id="prodetalle" required>
                    </div>
                    <div class="required field">
                        <label for="procantstock">Cantidad en Stock:</label>
                        <input name="procantstock" id="procantstock" required>
                    </div>
                    <div class="required field">
                        <label for="proprecio">Precio:</label>
                        <input name="proprecio" id="proprecio" required>
                    </div>
                </form>
            </div>
            <div class="actions">
                <button class="ui button" onclick="saveProducto()">Aceptar</button>
                <button class="ui button" onclick="closeDialog()">Cancelar</button>
            </div>
        </div>

        <div id="dlgConfirmDelete" class="ui modal">
            <div class="header">Confirmar Eliminación</div>
            <div class="content">
                <p>¿Seguro que desea eliminar el producto?</p>
                <div id="deleteMessageContainer" class="ui hidden message"></div> <!-- Contenedor para los mensajes dentro del modal de eliminación -->
            </div>
            <div class="actions">
                <button class="ui button" onclick="destroyProducto()">Eliminar</button>
                <button class="ui button" onclick="closeConfirmDialog()">Cancelar</button>
            </div>
        </div>

        <script>
            var url;
            var idProductoEliminar;

            $(document).ready(function() {
                $('.ui.dropdown').dropdown();
                $('#fmProductos').form({
                    fields: {
                        pronombre: 'empty',
                        prodetalle: 'empty',
                        procantstock: 'empty',
                        proprecio: 'empty'
                    }
                });
            });

            function newProducto() {
                $('#dlgProductos').modal('show');
                $('#fmProductos')[0].reset();
                $('#modalMessageContainer').addClass('hidden'); // Ocultar mensajes anteriores
                url = 'Accion/accionProductoTabla.php?accion=alta';
            }

            function editProducto(idproducto) {
                var row = $('tr[data-id="' + idproducto + '"]');
                if (row) {
                    $('#dlgProductos').modal('show');
                    $('#fmProductos')[0].reset();
                    $('#modalMessageContainer').addClass('hidden'); // Ocultar mensajes anteriores
                    $('#idproducto').val(idproducto);
                    $('#pronombre').val(row.find('td[data-field="pronombre"]').text());
                    $('#prodetalle').val(row.find('td[data-field="prodetalle"]').text());
                    $('#procantstock').val(row.find('td[data-field="procantstock"]').text());
                    $('#proprecio').val(row.find('td[data-field="proprecio"]').text());
                    url = 'Accion/accionProductoTabla.php?accion=mod&idproducto=' + idproducto;
                }
            }

            function saveProducto() {
                if ($('#fmProductos').form('is valid')) {
                    var formData = $('#fmProductos').serializeArray();
                    $.post(url, formData, function(result) {
                        try {
                            var result = JSON.parse(result);
                            if (!result.respuesta) {
                                showModalMessage('error', 'Error: ' + result.errorMsg);
                            } else {
                                $('#dlgProductos').modal('hide');
                                loadProductos(); // Reload the table data
                                showMessage('success', 'Producto guardado exitosamente.');
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            console.error('Response:', result);
                            showModalMessage('error', 'Error: No se pudo procesar la respuesta del servidor.');
                        }
                    });
                } else {
                    showModalMessage('error', 'Por favor, complete todos los campos requeridos.');
                }
            }

            function confirmDestroyProducto(idproducto) {
                idProductoEliminar = idproducto;
                $('#dlgConfirmDelete').modal('show');
            }

            function destroyProducto() {
                $.post('Accion/accionProductoTabla.php?accion=baja&idproducto=' + idProductoEliminar, function(result) {
                    try {
                        var result = JSON.parse(result);
                        if (result.respuesta) {
                            loadProductos(); // Reload the table data
                            showMessage('success', 'Producto eliminado exitosamente.');
                        } else {
                            showDeleteMessage('error', 'Error: ' + result.errorMsg);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        console.error('Response:', result);
                        showDeleteMessage('error', 'Error: No se pudo procesar la respuesta del servidor.');
                    }
                    $('#dlgConfirmDelete').modal('hide');
                });
            }

            function closeDialog() {
                $('#dlgProductos').modal('hide');
            }

            function closeConfirmDialog() {
                $('#dlgConfirmDelete').modal('hide');
            }

            function loadProductos() {
                $.get('Accion/accionProductoTabla.php?accion=listar', function(data) {
                    var productos = JSON.parse(data);
                    var tableBody = $('#productosTableBody');
                    tableBody.empty();
                    productos.forEach(function(producto) {
                        var row = '<tr data-id="' + producto.idproducto + '">' +
                            '<td data-field="idproducto">' + producto.idproducto + '</td>' +
                            '<td data-field="pronombre">' + producto.pronombre + '</td>' +
                            '<td data-field="prodetalle">' + producto.prodetalle + '</td>' +
                            '<td data-field="procantstock">' + producto.procantstock + '</td>' +
                            '<td data-field="proprecio">' + producto.proprecio + '</td>' +
                            '<td>' +
                            '<button class="ui button" onclick="editProducto(' + producto.idproducto + ')">Editar</button>' +
                            '<button class="ui button" onclick="confirmDestroyProducto(' + producto.idproducto + ')">Eliminar</button>' +
                            '</td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
                });
            }

            function showMessage(type, message) {
                var messageContainer = $('#messageContainer');
                messageContainer.removeClass('hidden');
                messageContainer.removeClass('success error');
                messageContainer.addClass(type);
                messageContainer.html(message);
            }

            function showModalMessage(type, message) {
                var messageContainer = $('#modalMessageContainer');
                messageContainer.removeClass('hidden');
                messageContainer.removeClass('success error');
                messageContainer.addClass(type);
                messageContainer.html(message);
            }

            function showDeleteMessage(type, message) {
                var messageContainer = $('#deleteMessageContainer');
                messageContainer.removeClass('hidden');
                messageContainer.removeClass('success error');
                messageContainer.addClass(type);
                messageContainer.html(message);
            }

            // Load the initial data
            $(document).ready(function() {
                loadProductos();
            });
        </script>

    </div>

</div>