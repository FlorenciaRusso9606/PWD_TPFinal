<?php
include_once "../configuracion.php";
include_once "../Control/pagPublica.php";  
$objControl = new AbmRol();
$List_Rol = $objControl->buscar(null);
?>

<?php include_once "../Estructura/header.php";
if ($rol !== 1) {
    header("Location: index.php");
    exit();
}
 ?>

<div class="ui hidden divider"></div>
<div class="ui container grid center aligned">
    <div class="ui sixteen wide column">
        <h2>ABM - Roles</h2>
        <p>Seleccione la acci&oacute;n que desea realizar.</p>

        <div id="messageContainer" class="ui hidden message"></div> <!-- Contenedor para los mensajes -->

        <table class="ui celled table">
            <thead>
                <tr>
                    <th class="two wide">ID</th>
                    <th class="ten wide">Descripción</th>
                    <th class="four wide">Acciones</th>
                </tr>
            </thead>
            <tbody id="rolesTableBody">
                <?php foreach ($List_Rol as $rol) : ?>
                    <tr data-id="<?php echo $rol->getidrol(); ?>">
                        <td data-field="idrol"><?php echo $rol->getidrol(); ?></td>
                        <td data-field="roldescripcion"><?php echo $rol->getroldescripcion(); ?></td>
                        <td>
                            <button class="ui button" onclick="editRol(<?php echo $rol->getidrol(); ?>)">Editar</button>
                            <button class="ui button" onclick="confirmDestroyRol(<?php echo $rol->getidrol(); ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="ui buttons">
            <button class="ui button" onclick="newRol()">Nuevo Rol</button>
        </div>

        <div id="dlgRoles" class="ui modal">
            <div class="header">Información del Rol</div>
            <div class="content">
                <div id="modalMessageContainer" class="ui hidden message">
                    <!-- Contenedor para los mensajes dentro del modal -->
                </div>
                <form id="fmRoles" class="ui form">
                    <input name="idrol" id="idrol" type="hidden">
                    <div class="required field">
                        <label for="roldescripcion">Descripción:</label>
                        <input name="roldescripcion" id="roldescripcion" required>
                    </div>
                </form>
            </div>
            <div class="actions">
                <button class="ui button" onclick="saveRol()">Aceptar</button>
                <button class="ui button" onclick="closeDialog()">Cancelar</button>
            </div>
        </div>

        <div id="dlgConfirmDelete" class="ui modal">
            <div class="header">Confirmar Eliminación</div>
            <div class="content">
                <p>¿Seguro que desea eliminar el rol?</p>
                <div id="deleteMessageContainer" class="ui hidden message"></div> <!-- Contenedor para los mensajes dentro del modal de eliminación -->
            </div>
            <div class="actions">
                <button class="ui button" onclick="destroyRol()">Eliminar</button>
                <button class="ui button" onclick="closeConfirmDialog()">Cancelar</button>
            </div>
        </div>

        <script>
            var url;
            var idRolEliminar;

            $(document).ready(function() {
                $('.ui.dropdown').dropdown();
                $('#fmRoles').form({
                    fields: {
                        roldescripcion: 'empty'
                    }
                });
            });

            function newRol() {
                $('#dlgRoles').modal('show');
                $('#fmRoles')[0].reset();
                $('#modalMessageContainer').addClass('hidden'); // Ocultar mensajes anteriores
                url = 'Accion/accionRoles.php?accion=alta';
            }

            function editRol(idrol) {
                var row = $('tr[data-id="' + idrol + '"]');
                if (row) {
                    $('#dlgRoles').modal('show');
                    $('#fmRoles')[0].reset();
                    $('#modalMessageContainer').addClass('hidden'); // Ocultar mensajes anteriores
                    $('#idrol').val(idrol);
                    $('#roldescripcion').val(row.find('td[data-field="roldescripcion"]').text());
                    url = 'Accion/accionRoles.php?accion=mod&idrol=' + idrol;
                }
            }

            function saveRol() {
                if ($('#fmRoles').form('is valid')) {
                    var formData = $('#fmRoles').serializeArray();
                    $.post(url, formData, function(result) {
                        try {
                            var result = JSON.parse(result);
                            if (!result.respuesta) {
                                showModalMessage('error', 'Error: ' + result.errorMsg);
                            } else {
                                $('#dlgRoles').modal('hide');
                                loadRoles(); // Reload the table data
                                showMessage('success', 'Rol guardado exitosamente.');
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

            function confirmDestroyRol(idrol) {
                idRolEliminar = idrol;
                $('#dlgConfirmDelete').modal('show');
            }

            function destroyRol() {
                $.post('Accion/accionRoles.php?accion=baja&idrol=' + idRolEliminar, function(result) {
                    try {
                        var result = JSON.parse(result);
                        if (result.respuesta) {
                            loadRoles(); // Reload the table data
                            showMessage('success', 'Rol eliminado exitosamente.');
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
                $('#dlgRoles').modal('hide');
            }

            function closeConfirmDialog() {
                $('#dlgConfirmDelete').modal('hide');
            }

            function loadRoles() {
                $.get('Accion/accionRoles.php?accion=listar', function(data) {
                    var roles = JSON.parse(data);
                    var tableBody = $('#rolesTableBody');
                    tableBody.empty();
                    roles.forEach(function(rol) {
                        var row = '<tr data-id="' + rol.idrol + '">' +
                            '<td data-field="idrol">' + rol.idrol + '</td>' +
                            '<td data-field="roldescripcion">' + rol.roldescripcion + '</td>' +
                            '<td>' +
                            '<button class="ui button" onclick="editRol(' + rol.idrol + ')">Editar</button>' +
                            '<button class="ui button" onclick="confirmDestroyRol(' + rol.idrol + ')">Eliminar</button>' +
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
                loadRoles();
            });
        </script>
    </div>
</div>
<?php include_once "../Estructura/footer.php";?>