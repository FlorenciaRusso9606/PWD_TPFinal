<?php
include_once "../configuracion.php";
include_once "../Control/pagPublica.php";
$objControl = new AbmMenu();
$List_Menu = $objControl->buscar(null);
$combo = '<select class="easyui-combobox" id="idpadre" name="idpadre" label="Submenu de?:" labelPosition="top" style="width:90%;" required>
<option></option>';
foreach ($List_Menu as $objMenu) {
    $combo .= '<option value="' . $objMenu->getIdmenu() . '">' . $objMenu->getMenombre() . ':' . $objMenu->getMedescripcion() . '</option>';
}
$combo .= '</select>';
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
        <h2>ABM - Menu</h2>
        <p>Seleccione la acci&oacute;n que desea realizar.</p>

        <table id="dg" title="Administrador de item menu" class="easyui-datagrid" style="width:700px;height:400px"
            url="Accion/accionMenu.php?accion=listar" toolbar="#toolbar" pagination="true" rownumbers="true" fitColumns="true" singleSelect="true">
            <thead>
                <tr>
                    <th field="idmenu" width="50">ID</th>
                    <th field="menombre" width="50">Nombre</th>
                    <th field="medescripcion" width="50">Descripci&oacute;n</th>
                    <th field="idpadre" width="50">Submenu De:</th>
                    <th field="medeshabilitado" width="50">Deshabilitado</th>
                </tr>
            </thead>
        </table>
        <div id="toolbar">
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newMenu()">Nuevo Menu </a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editMenu()">Editar Menu</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyMenu()">Baja Menu</a>
        </div>

        <div id="dlg" class="easyui-dialog" style="width:600px" data-options="closed:true,modal:true,border:'thin',buttons:'#dlg-buttons'">
            <form id="fm" method="post" novalidate style="margin:0;padding:20px 50px">
                <h3>Menu Informacion</h3>
                <div style="margin-bottom:10px">
                    <input name="idmenu" id="idmenu" type="hidden">
                    <input name="menombre" id="menombre" class="easyui-textbox" required="true" label="Nombre:" style="width:100%">
                </div>
                <div style="margin-bottom:10px">
                    <input name="medescripcion" id="medescripcion" class="easyui-textbox" required="true" label="Descripcion:" style="width:100%">
                </div>
                <div style="margin-bottom:10px">
                    <?php echo $combo; ?>
                </div>
                <div style="margin-bottom:10px">
                    <input class="easyui-checkbox" name="medeshabilitado" value="medeshabilitado" label="Des-Habilitar:">
                </div>
            </form>
        </div>
        <div id="dlg-buttons">
            <a href="javascript:void(0)" class="easyui-linkbutton c6" iconCls="icon-ok" onclick="saveMenu()" style="width:90px">Aceptar</a>
            <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">Cancelar</a>
        </div>
        <script type="text/javascript">
            var url;

            function newMenu() {
                $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Nuevo Menu');
                $('#fm').form('clear');
                url = 'Accion/accionMenu.php?accion=alta';
            }

            function editMenu() {
                var row = $('#dg').datagrid('getSelected');
                if (row) {
                    $('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Editar Menu');
                    $('#fm').form('load', row);
                    $('#idpadre').combobox('setValue', row.idpadre); // Asegúrate de que el valor del combo se establezca correctamente
                    url = 'Accion/accionMenu.php?accion=mod&idmenu=' + row.idmenu;
                }
            }

            function saveMenu() {
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

            function destroyMenu() {
                var row = $('#dg').datagrid('getSelected');
                if (row) {
                    $.messager.confirm('Confirm', 'Seguro que desea eliminar el menu?', function(r) {
                        if (r) {
                            $.post('Accion/accionMenu.php?accion=baja&idmenu=' + row.idmenu, {
                                    idmenu: row.id
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