<?php
include_once "../Estructura/header.php";

$session = new Session();
$idUsuario = $session->getUsuario();

// Por si se ingresa a través de la URL
if ($idUsuario == null) {
    header("Location: " . $PRINCIPAL);
}

$res = false;
if ($idUsuario) {
    $abmUsuario = new AbmUsuario();
    $usuario = $abmUsuario->buscar(['idusuario' => $idUsuario]);
    var_dump($usuario);
    if (count($usuario) > 0) {
        $usuario = $usuario[0];
        $res = true;
    }
}
?>

<div class="w-50 mx-auto pb-3 mt-4">
    <div class="sixteen wide column">
        <div class="ui center aligned padded segment container grid">
            <div class="ui four wide column">
                <div class="image">
                    <img class="ui massive image" src="./img/Productos/android-chrome-192x192.png" alt="Defualt-Profile-Picture">
                </div>
            </div>
            <div class="ui ten wide column">
                <?php
                if ($res == true) {
                    echo "<h1>Información del Usuario</h1>";
                    echo "<p><strong>Nombre de usuario:</strong> " . $usuario->getUsuarioNombre() . "</p>";
                    echo "<p><strong>Email:</strong> " . $usuario->getUsuarioEmail() . "</p>";
                    echo "<p><strong>Contraseña:</strong> " . str_repeat('*', strlen($usuario->getUsuarioPassword())) . "</p>";

                    // Botón Editar
                    echo '<button class="btn btn-primary mt-3" onclick="editarUsuario(' . $usuario->getUsuarioId() . ')">Editar</button>';
                } else {
                    echo "<h1>No se encontró el usuario</h1>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

<!-- Formulario de edición oculto inicialmente -->
<div id="editarUsuarioForm" class="easyui-panel" title="Editar Usuario" style="width:300px;padding:10px;display:none;">
    <form id="userEdit" method="post" enctype="multipart/form-data">
        <table>
            <tr>
                <td>Nombre:</td>
                <td><input type="text" name="name" class="f1 easyui-textbox" required></input></td>
            </tr>
            <tr>
                <td>Email:</td>
                <td><input type="email" name="email" class="f1 easyui-textbox" required></input></td>
            </tr>
            <tr>
                <td>Contraseña:</td>
                <td><input name="pass" class="f1 easyui-textbox" required></input></td>
            </tr>
            <tr>
                <td></td>
                <td><input type="submit" value="Guardar"></input></td>
            </tr>
        </table>
    </form>
</div>

<style scoped>
    .f1 {
        width: 200px;
    }
</style>

<script type="text/javascript">
    var url;

    // Función para mostrar el formulario de edición con AJAX
    function editarUsuario(idUsuario) {
        $.ajax({
            url: './Accion/accionEditarUsuario.php',
            type: 'GET',
            data: {
                idusuario: idUsuario
            },
            success: function(data) {
                // Mostrar el formulario de edición con los datos cargados
                $('#editarUsuarioForm').html(data).show();
                $('#editarUsuarioForm').dialog('open').dialog('center').dialog('setTitle', 'Editar Usuario');
            }
        });
    }

    $(function() {
        // Configuración del formulario
        $('#userEdit').form({
            iframe: false,
            success: function(data) {
                $.messager.alert('Info', data, 'info');
                // Cerrar el formulario después de guardar
                $('#editarUsuarioForm').hide();
            }
        });
    });
</script>

<?php include_once "../Estructura/footer.php"; ?>