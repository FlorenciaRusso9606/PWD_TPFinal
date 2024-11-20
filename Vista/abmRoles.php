<?php
include_once "../configuracion.php";
                                    include_once "../Control/pagPublica.php";  
$objControl = new AbmRol();
$List_Rol = $objControl->buscar(null);
$combo = '<select class="easyui-combobox" id="idrol" name="idrol" label="Rol:" labelPosition="top" style="width:90%;" required>
<option></option>';
foreach ($List_Rol as $objRol) {
    $combo .= '<option value="' . $objRol->getidrol() . '">' . $objRol->getroldescripcion() . '</option>';
}
$combo .= '</select>';

$objControlUsuario = new ABMUsuario();
$List_Usuario = $objControlUsuario->buscar(null);
$comboUsuario = '<select class="easyui-combobox" id="idusuario" name="idusuario" label="Usuario:" labelPosition="top" style="width:90%;" required>
<option></option>';
foreach ($List_Usuario as $objUsuario) {
    $comboUsuario .= '<option value="' . $objUsuario->getUsuarioId() . '">' . $objUsuario->getUsuarioNombre() . '</option>';
}
$comboUsuario .= '</select>';
?>

<?php include_once "../Estructura/header.php"; ?>

<!-- Link EasyUI -->
<link rel="stylesheet" type="text/css" href="<?= $RUTAVISTA ?>js/jquery-easyui-1.6.6/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="<?= $RUTAVISTA ?>js/jquery-easyui-1.6.6/themes/icon.css">
<link rel="stylesheet" type="text/css" href="<?= $RUTAVISTA ?>js/jquery-easyui-1.6.6/themes/color.css">
<link rel="stylesheet" type="text/css" href="<?= $RUTAVISTA ?>js/jquery-easyui-1.6.6/demo/demo.css">
<script type="text/javascript" src="<?= $RUTAVISTA ?>js/jquery-easyui-1.6.6/jquery.min.js"></script>
<script type="text/javascript" src="<?= $RUTAVISTA ?>js/jquery-easyui-1.6.6/jquery.easyui.min.js"></script>

<div class="ui hidden divider"></div>

<div class="ui container grid center aligned">
    <div class="ui sixteen wide column">
        <h2>ABM - Roles</h2>
        <p>Seleccione la acci&oacute;n que desea realizar.</p>

        <table id="dg" title="Administrador de roles" class="easyui-datagrid" style="width:700px;height:400px"
            url="Accion/accionRoles.php?accion=listar" toolbar="#toolbar" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true">
            <thead>
                <tr>
                    <th field="idrol" width="50">ID</th>
                    <th field="roldescripcion" width="50">Descripción</th>
                </tr>
            </thead>
        </table>
        <div id="toolbar">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newRol()">Nuevo Rol</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editRol()">Editar Rol</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyRol()">Baja Rol</a>
        </div>

        <div id="dlg" class="easyui-dialog" style="width:600px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
            <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
                <h3>Rol Información</h3>
                <div style="margin-bottom:10px">
                    <input name="idrol" id="idrol" type="hidden">
                    <input name="roldescripcion" id="roldescripcion" class="easyui-textbox" required="true" label="Descripción:" style="width:100%">
                </div>
            </form>
        </div>
        <div id="dlg-buttons">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveRol()" style="width:90px">Aceptar</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
        </div>



        <script type="text/javascript">
            var url;

            function newRol() {
                $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo Rol');
                $('#fm').form('clear');
                url = 'Accion/accionRoles.php?accion=alta';
            }

            function editRol() {
                var row = $('#dg').datagrid('getSelected');
                if (row) {
                    $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar Rol');
                    $('#fm').form('load', row);
                    url = 'Accion/accionRoles.php?accion=mod&idrol=' + row.idrol;
                }
            }

            function saveRol() {
                $('#fm').form('submit', {
                    url: url,
                    onSubmit: function() {
                        return $(this).form('validate');
                    },
                    success: function(result) {
                        var result = eval('(' + result + ')');
                        if (!result.respuesta) {
                            $.messager.show({
                                title: 'Error',
                                msg: result.errorMsg
                            });
                        } else {
                            $('#dlg').dialog('close'); // close the dialog
                            $('#dg').datagrid('reload'); // reload 
                        }
                    }
                });
            }

            function destroyRol() {
                var row = $('#dg').datagrid('getSelected');
                if (row) {
                    $.messager.confirm('Confirm', 'Seguro que desea eliminar el rol?', function(r) {
                        if (r) {
                            $.post('Accion/accionRoles.php?accion=baja&idrol=' + row.idrol, {
                                    idrol: row.idrol
                                },
                                function(result) {
                                    if (result.respuesta) {
                                        $('#dg').datagrid('reload'); // reload the  data
                                    } else {
                                        $.messager.show({ // show error message
                                            title: 'Error',
                                            msg: result.errorMsg
                                        });
                                    }
                                }, 'json');
                        }
                    });
                }
            }
        </script>
    </div>
</div>