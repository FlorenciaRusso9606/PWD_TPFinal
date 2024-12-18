<!DOCTYPE html>
<html lang="en">

<?php
include_once "../Control/pagPublica.php";
include_once "../Estructura/header.php";
include_once "../configuracion.php";
?>

<div class="ui modal" id="mensajeModal">
    <div class="header" id="mensajeModalHeader"></div>
    <div class="content">
        <p id="mensajeModalContent"></p>
    </div>
    <div class="actions">
        <div class="ui approve button">OK</div>
    </div>
</div>

<div class="ui horizontal divider"></div>
<h2 class="ui container center aligned header my-5">Estado de Compra</h2>

<div class="ui container" id="comprasContainer">
    <?php
    $sesion = new Session;
    $abmEstadoTipo = new ABMcompraEstadoTipo;
    $abmCompraItem = new AbmCompraItem();
    $controlCompra = new ControlCompra;
    $compras = $controlCompra->buscarCompras($sesion);

    $ambCompraEstado = new AbmCompraEstado();

    foreach ($compras as $compra) {
        $idCompra = $compra->getIdCompra();
        $paramIdCompra = ["idcompra" => $idCompra];
        $estado = $ambCompraEstado->buscar($paramIdCompra);

        if (!$estado) {
            continue;
        }

        $arrItems = $abmCompraItem->buscar($paramIdCompra);
        $precioTotal = array_reduce($arrItems, function ($carry, $item) {
            return $carry + $item->getObjProducto()->getProPrecio() * $item->getCiCantidad();
        }, 0);


        //Contador de estado actual
        if (count($estado) >= 1) {
            $cantidadEstado = count($estado) - 1;
        }

        $idTipoEstado = $estado[$cantidadEstado]->getobjCompraEstadoTipo()->getidcompraestadotipo();
        /* var_dump($idTipoEstado); */
        $descripcionEstado = $estado[$cantidadEstado]->getobjCompraEstadoTipo()->getCetDescripcion();

        echo "
        <div class='ui raised segment my-4' data-id='{$idCompra}'>
            <div class='content'>
                <h3 class='ui header'>Compra ID: $idCompra</h3>
                <p><strong>Estado:</strong> <span class='estado-text'>{$descripcionEstado}</span></p>
                <div class='ui list'>";

        foreach ($arrItems as $item) {
            echo "
                    <div class='item'>
                        {$item->getobjProducto()->getProNombre()} 
                        <span class='ui label'>Cantidad: {$item->getCiCantidad()}</span>
                    </div>";
        }

        echo "
                </div>
                <p><strong>Total:</strong> $$precioTotal</p>";

        if ($session->getRol() == 2 && $idTipoEstado !== 4 && $idTipoEstado !== 3) {
            echo "
            <form class='ui form form-cambiar-estado' data-id='{$idCompra}' method='POST'>
                <div class='field'>
                    <label>Cambiar estado</label>
                    <select class='ui dropdown' name='nuevoestado'>
                        " . ($idTipoEstado == 1 ? "<option value='2'>Aceptada</option>" : "") . "
                        " . ($idTipoEstado == 2 ? "<option value='3'>Enviada</option>" : "") . "
                    </select>
                </div>
                <input type='hidden' name='idcompra' value='{$idCompra}'>
                <button class='ui primary button w-100' type='submit'>Confirmar cambio</button>
            </form>";
        }

        echo "
                <form class='ui form form-cancelar-compra mt-3' data-id='{$idCompra}' method='POST'>
                    <input type='hidden' name='idcompra' value='{$idCompra}'>
                     <input type='hidden' name='nuevoestado' value='4'>
                    <button class='ui red button w-100' type='submit' " . ($idTipoEstado == 4 || $idTipoEstado == 3 ? "disabled" : "") . ">Cancelar Compra</button>
                </form>
            </div>
        </div>";
    }
    ?>
</div>

<script>
    $(document).ready(function() {
        // Mostrar un mensaje en el modal
        const mostrarMensaje = (tipo, mensaje) => {
            const header = tipo === "success" ? "Éxito" : "Error";
            $("#mensajeModalHeader").text(header);
            $("#mensajeModalContent").text(mensaje);
            $("#mensajeModal").modal("show");
        };

        /*const recargarCompras = () => {
            $.ajax({
                url: "compraEstado.php",
                method: "GET",
                success: function (html) {
                    $("#comprasContainer").html(html);
                    asociarEventos();
                },
                error: function (error) {
                    console.error("Error al recargar compras:", error);
                }
            });
        };*/

        // Enviar formulario con AJAX
        const enviarFormulario = (form, url, mensajeExito, mensajeError) => {
            const formData = new FormData(form);

            $.ajax({
                url: url,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    if (data.success) {
                        mostrarMensaje("success", mensajeExito);
                        //recargarCompras(); // Recargar el contenido de compras
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    } else {
                        mostrarMensaje("error", mensajeError);
                    }
                },
                error: function(error) {
                    console.error("Error:", error);
                    mostrarMensaje("error", "Ocurrió un error. Intenta nuevamente.");
                }
            });
        };

        // Asociar eventos a formularios
        const asociarEventos = () => {
            $(".form-cambiar-estado").on("submit", function(event) {
                event.preventDefault();
                enviarFormulario(
                    this,
                    "Accion/cambiarEstado.php",
                    "Estado cambiado exitosamente.",
                    "No se pudo cambiar el estado."
                );
            });

            $(".form-cancelar-compra").on("submit", function(event) {
                event.preventDefault();
                enviarFormulario(
                    this,
                    "Accion/cambiarEstado.php",
                    "Compra cancelada exitosamente.",
                    "No se pudo cancelar la compra."
                );
            });
        };

        // Inicializar eventos al cargar la página
        asociarEventos();
    });
</script>

<?php
include_once "../Estructura/footer.php";
?>