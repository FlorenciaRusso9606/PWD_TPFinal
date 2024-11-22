<?php
include_once "../configuracion.php";
include_once "../Control/pagPublica.php";



$objControl = new AbmMenu();
$List_Menu = $objControl->buscar(null);
$combo = '<select class="ui dropdown" id="idpadre" name="idpadre" required>
<option value="">Seleccione Submenu</option>';
foreach ($List_Menu as $objMenu) {
    $combo .= '<option value="' . $objMenu->getIdmenu() . '">' . $objMenu->getMenombre() . ':' . $objMenu->getMedescripcion() . '</option>';
}
$combo .= '</select>';
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
        <h2>ABM - Menu</h2>
        <p>Seleccione la acci&oacute;n que desea realizar.</p>

        <div id="messageContainer" class="ui hidden message"></div> <!-- Contenedor para los mensajes -->

        <table class="ui celled table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripci&oacute;n</th>
                    <th>Submenu De:</th>
                    <th>Deshabilitado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="menuTableBody">
                <?php foreach ($List_Menu as $menu) : ?>
                    <tr data-id="<?php echo $menu->getIdmenu(); ?>">
                        <td data-field="idmenu"><?php echo $menu->getIdmenu(); ?></td>
                        <td data-field="menombre"><?php echo $menu->getMenombre(); ?></td>
                        <td data-field="medescripcion"><?php echo $menu->getMedescripcion(); ?></td>
                        <td data-field="idpadre"><?php echo $menu->getObjMenuPadre() ? $menu->getObjMenuPadre()->getIdmenu() : 'N/A'; ?></td>
                        <td data-field="medeshabilitado"><?php echo $menu->getMedeshabilitado(); ?></td>
                        <td>
                            <button class="ui button" onclick="editMenu(<?php echo $menu->getIdmenu(); ?>)">Editar</button>
                            <button class="ui button" onclick="confirmDestroyMenu(<?php echo $menu->getIdmenu(); ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="ui buttons">
            <button class="ui button" onclick="newMenu()">Nuevo Menu</button>
        </div>

        <div id="dlgMenu" class="ui modal">
            <div class="header">Información del Menu</div>
            <div class="content">
                <div id="modalMessageContainer" class="ui hidden message">
                    <!-- Contenedor para los mensajes dentro del modal -->
                </div>
                <form id="fmMenu" class="ui form">
                    <input name="idmenu" id="idmenu" type="hidden">
                    <div class="required field">
                        <label for="menombre">Nombre:</label>
                        <input name="menombre" id="menombre" required>
                    </div>
                    <div class="required field">
                        <label for="medescripcion">Descripci&oacute;n:</label>
                        <input name="medescripcion" id="medescripcion" required>
                    </div>
                    <div class="field">
                        <label for="idpadre">Submenu De:</label>
                        <?php echo $combo; ?>
                    </div>
                    <div class="field">
                        <div class="ui checkbox">
                            <input type="checkbox" name="medeshabilitado" id="medeshabilitado">
                            <label for="medeshabilitado">Deshabilitado</label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="actions">
                <button class="ui button" onclick="saveMenu()">Aceptar</button>
                <button class="ui button" onclick="closeDialog()">Cancelar</button>
            </div>
        </div>

        <div id="dlgConfirmDelete" class="ui modal">
            <div class="header">Confirmar Eliminación</div>
            <div class="content">
                <p>¿Seguro que desea eliminar el menu?</p>
                <div id="deleteMessageContainer" class="ui hidden message"></div> <!-- Contenedor para los mensajes dentro del modal de eliminación -->
            </div>
            <div class="actions">
                <button class="ui button" onclick="destroyMenu()">Eliminar</button>
                <button class="ui button" onclick="closeConfirmDialog()">Cancelar</button>
            </div>
        </div>

        <script>
            var url;
            var idMenuEliminar;

            $(document).ready(function() {
                $('.ui.dropdown').dropdown();
                $('#fmMenu').form({
                    fields: {
                        menombre: 'empty',
                        medescripcion: 'empty',
                    }
                });
            });

            function newMenu() {
                $('#dlgMenu').modal('show');
                $('#fmMenu')[0].reset();
                $('#modalMessageContainer').addClass('hidden'); // Ocultar mensajes anteriores
                url = 'Accion/accionMenu.php?accion=alta';
            }

            function editMenu(idmenu) {
                var row = $('tr[data-id="' + idmenu + '"]');
                if (row) {
                    $('#dlgMenu').modal('show');
                    $('#fmMenu')[0].reset();
                    $('#modalMessageContainer').addClass('hidden'); // Ocultar mensajes anteriores
                    $('#idmenu').val(idmenu);
                    $('#menombre').val(row.find('td[data-field="menombre"]').text());
                    $('#medescripcion').val(row.find('td[data-field="medescripcion"]').text());
                    $('#idpadre').dropdown('set selected', row.find('td[data-field="idpadre"]').text());
                    $('#medeshabilitado').prop('checked', row.find('td[data-field="medeshabilitado"]').text() === '1');
                    url = 'Accion/accionMenu.php?accion=mod&idmenu=' + idmenu;
                }
            }

            function saveMenu() {
                if ($('#fmMenu').form('is valid')) {
                    var formData = $('#fmMenu').serializeArray();
                    $.post(url, formData, function(result) {
                        try {
                            var result = JSON.parse(result);
                            if (!result.respuesta) {
                                showModalMessage('error', 'Error: ' + result.errorMsg);
                            } else {
                                $('#dlgMenu').modal('hide');
                                loadMenus(); // Reload the table data
                                showMessage('success', 'Menu guardado exitosamente.');
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

            function confirmDestroyMenu(idmenu) {
                idMenuEliminar = idmenu;
                $('#dlgConfirmDelete').modal('show');
            }

            function destroyMenu() {
                $.post('Accion/accionMenu.php?accion=baja&idmenu=' + idMenuEliminar, function(result) {
                    try {
                        var result = JSON.parse(result);
                        if (result.respuesta) {
                            loadMenus(); // Reload the table data
                            showMessage('success', 'Menu eliminado exitosamente.');
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
                $('#dlgMenu').modal('hide');
            }

            function closeConfirmDialog() {
                $('#dlgConfirmDelete').modal('hide');
            }

            function loadMenus() {
                $.get('Accion/accionMenu.php?accion=listar', function(data) {
                    var menus = JSON.parse(data);
                    var tableBody = $('#menuTableBody');
                    tableBody.empty();
                    menus.forEach(function(menu) {
                        var row = '<tr data-id="' + menu.idmenu + '">' +
                            '<td data-field="idmenu">' + menu.idmenu + '</td>' +
                            '<td data-field="menombre">' + menu.menombre + '</td>' +
                            '<td data-field="medescripcion">' + menu.medescripcion + '</td>' +
                            '<td data-field="idpadre">' + (menu.idpadre ? menu.idpadre : 'N/A') + '</td>' +
                            '<td data-field="medeshabilitado">' + menu.medeshabilitado + '</td>' +
                            '<td>' +
                            '<button class="ui button" onclick="editMenu(' + menu.idmenu + ')">Editar</button>' +
                            '<button class="ui button" onclick="confirmDestroyMenu(' + menu.idmenu + ')">Eliminar</button>' +
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
                loadMenus();
            });
        </script>
    </div>
</div>
<? include_once "../Estructura/footer.php";
