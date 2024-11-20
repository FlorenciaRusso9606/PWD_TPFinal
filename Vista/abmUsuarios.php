<?php
include_once "../configuracion.php";
$objControl = new AbmRol();
$List_Rol = $objControl->buscar(null);
$combo = '<select id="idrol" name="idrol" class="ui dropdown" required>
<option></option>';
foreach ($List_Rol as $objRol) {
    $combo .= '<option value="' . $objRol->getidrol() . '">' . $objRol->getroldescripcion() . '</option>';
}
$combo .= '</select>';

$objControlUsuario = new ABMUsuario();
$abmUsuarioRol = new abmUsuarioRol(); // Definir la variable $abmUsuarioRol
$List_Usuario = $objControlUsuario->buscar(null);
?>

<?php include_once "../Estructura/header.php"; ?>

<!-- Link Semantic UI -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js"></script>

<div class="ui hidden divider"></div>
<div class="ui container grid center aligned">

    <div class="ui sixteen wide column">

        <h2>ABM - Usuarios y Roles</h2>
        <p>Seleccione la acci&oacute;n que desea realizar.</p>

        <table class="ui celled table">
            <thead>
                <tr>
                    <th>ID Usuario</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>ID Rol</th>
                    <th>Descripción Rol</th>
                    <th>Deshabilitado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="usuariosTableBody">
                <?php foreach ($List_Usuario as $usuario) : ?>
                    <?php
                    $roles = $abmUsuarioRol->buscar(['idusuario' => $usuario->getUsuarioId()]);
                    $rol = count($roles) > 0 ? $roles[0]->getObjRol() : null;
                    ?>
                    <tr data-id="<?php echo $usuario->getUsuarioId(); ?>">
                        <td data-field="idusuario"><?php echo $usuario->getUsuarioId(); ?></td>
                        <td data-field="usnombre"><?php echo $usuario->getUsuarioNombre(); ?></td>
                        <td data-field="usmail"><?php echo $usuario->getUsuarioEmail(); ?></td>
                        <td data-field="idrol"><?php echo $rol ? $rol->getidrol() : ''; ?></td>
                        <td data-field="roldescripcion"><?php echo $rol ? $rol->getroldescripcion() : ''; ?></td>
                        <td data-field="usdeshabilitado"><?php echo $usuario->getUsuarioDeshabilitado(); ?></td>
                        <td>
                            <button class="ui button" onclick="editUsuarioRol(<?php echo $usuario->getUsuarioId(); ?>)">Editar</button>
                            <button class="ui button" onclick="destroyUsuarioRol(<?php echo $usuario->getUsuarioId(); ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="ui buttons">
            <button class="ui button" onclick="newUsuarioRol()">Nuevo Usuario/Rol</button>
        </div>

        <div id="dlgUsuarios" class="ui modal">
            <div class="header">Usuario y Rol Información</div>
            <div class="content">
                <form id="fmUsuarios" class="ui form">
                    <input name="idusuario" id="idusuario" type="hidden">
                    <div class="field">
                        <label for="usnombre">Nombre:</label>
                        <input name="usnombre" id="usnombre" required>
                    </div>
                    <div class="field">
                        <label for="usmail">Email:</label>
                        <input name="usmail" id="usmail" required>
                    </div>
                    <div class="field">
                        <label for="uspass">Password:</label>
                        <input name="uspass" id="uspass" required>
                    </div>
                    <div class="field">
                        <?php echo $combo; ?>
                    </div>
                    <div class="field">
                        <label for="usdeshabilitado">Deshabilitado:</label>
                        <input type="checkbox" name="usdeshabilitado" id="usdeshabilitado" value="true">
                    </div>
                </form>
            </div>
            <div class="actions">
                <button class="ui button" onclick="saveUsuarioRol()">Aceptar</button>
                <button class="ui button" onclick="closeDialog()">Cancelar</button>
            </div>
        </div>

        <script>
            var url;

            $(document).ready(function() {
                $('.ui.dropdown').dropdown();
            });

            function newUsuarioRol() {
                $('#dlgUsuarios').modal('show');
                $('#fmUsuarios')[0].reset();
                url = 'Accion/accionUsuarioTabla.php?accion=alta';
            }

            function editUsuarioRol(idusuario) {
                var row = $('tr[data-id="' + idusuario + '"]');
                if (row) {
                    $('#dlgUsuarios').modal('show');
                    $('#idusuario').val(idusuario);
                    $('#usnombre').val(row.find('td[data-field="usnombre"]').text());
                    $('#usmail').val(row.find('td[data-field="usmail"]').text());
                    $('#uspass').val(''); // Clear password field
                    $('#idrol').val(row.find('td[data-field="idrol"]').text());
                    $('#usdeshabilitado').prop('checked', row.find('td[data-field="usdeshabilitado"]').text() !== '');
                    url = 'Accion/accionUsuarioTabla.php?accion=mod&idusuario=' + idusuario;
                }
            }

            function saveUsuarioRol() {
                var formData = $('#fmUsuarios').serializeArray();
                formData.push({
                    name: 'usdeshabilitado',
                    value: $('#usdeshabilitado').is(':checked') ? 'true' : 'false'
                });
                $.post(url, formData, function(result) {
                    try {
                        var result = JSON.parse(result);
                        if (!result.respuesta) {
                            alert('Error: ' + result.errorMsg);
                        } else {
                            $('#dlgUsuarios').modal('hide');
                            loadUsuarios(); // Reload the table data
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e);
                        console.error('Response:', result);
                        alert('Error: No se pudo procesar la respuesta del servidor.');
                    }
                });
            }

            function destroyUsuarioRol(idusuario) {
                if (confirm('Seguro que desea eliminar el usuario/rol?')) {
                    $.post('Accion/accionUsuarioTabla.php?accion=baja&idusuario=' + idusuario, function(result) {
                        try {
                            var result = JSON.parse(result);
                            if (result.respuesta) {
                                loadUsuarios(); // Reload the table data
                            } else {
                                alert('Error: ' + result.errorMsg);
                            }
                        } catch (e) {
                            console.error('Error parsing JSON:', e);
                            console.error('Response:', result);
                            alert('Error: No se pudo procesar la respuesta del servidor.');
                        }
                    });
                }
            }

            function closeDialog() {
                $('#dlgUsuarios').modal('hide');
            }

            function loadUsuarios() {
                $.get('Accion/accionUsuarioTabla.php?accion=listar', function(data) {
                    var usuarios = JSON.parse(data);
                    var tableBody = $('#usuariosTableBody');
                    tableBody.empty();
                    usuarios.forEach(function(usuario) {
                        var row = '<tr data-id="' + usuario.idusuario + '">' +
                            '<td data-field="idusuario">' + usuario.idusuario + '</td>' +
                            '<td data-field="usnombre">' + usuario.usnombre + '</td>' +
                            '<td data-field="usmail">' + usuario.usmail + '</td>' +
                            '<td data-field="idrol">' + usuario.idrol + '</td>' +
                            '<td data-field="roldescripcion">' + usuario.roldescripcion + '</td>' +
                            '<td data-field="usdeshabilitado">' + usuario.usdeshabilitado + '</td>' +
                            '<td>' +
                            '<button class="ui button" onclick="editUsuarioRol(' + usuario.idusuario + ')">Editar</button>' +
                            '<button class="ui button" onclick="destroyUsuarioRol(' + usuario.idusuario + ')">Eliminar</button>' +
                            '</td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
                });
            }

            // Load the initial data
            $(document).ready(function() {
                loadUsuarios();
            });
        </script>

    </div>

</div>