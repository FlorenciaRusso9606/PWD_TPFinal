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

<div class="ui container">
    <?php

    $abmEstadoTipo = new ABMcompraEstadoTipo;
    $abmCompraItem = new AbmCompraItem();
    $controlCompra = new ControlCompra;
    $compras = $controlCompra->buscarCompras($session);
    $abmEstadoTipo = new ABMcompraEstadoTipo();
    $abmCompraItem = new AbmCompraItem();
    $ambCompraEstado = new AbmCompraEstado();
    $precioTotal = 0;
    foreach ($compras as $compra) {
        $idCompra = $compra->getIdCompra();
        $paramIdCompra = ["idcompra" => $idCompra];
        $precioTotal = 0;
        // Obtener estado
        $estado = $ambCompraEstado->buscar($paramIdCompra);
        if (!$estado) {
            continue;
        }

        // Obtener items de la compra
        $arrItems = $abmCompraItem->buscar($paramIdCompra);

        // Mostrar información de la compra
        echo "<div class='ui raised segment my-4' data-id='{$idCompra}'>"; // Asegúrate de que `data-id` tenga el valor correcto
        echo "<div class='content'>";
        echo "<h3 class='ui header'>Compra ID: $idCompra</h3>";
        $idTipoEstado["idcompraestadotipo"] = $estado[0]->getobjCompraEstadoTipo()->getidcompraestadotipo();
        echo "<p><strong>Estado:</strong> <span>{$estado[0]->getobjCompraEstadoTipo()->getCetDescripcion()}</span></p>";

        echo "<div class='ui list'>";
        foreach ($arrItems as $item) {
            echo "<div class='item'>";
            echo "{$item->getobjProducto()->getProNombre()} <span class='ui label'>Cantidad: {$item->getCiCantidad()}</span>";
            $precioTotal = $precioTotal + $item->getObjProducto()->getProPrecio() * $item->getCiCantidad();
            echo "</div>";
        }
        echo "</div>";
        echo "<p><strong>Total:</strong> $$precioTotal</p>";

        // Formulario para cambiar estado (si aplica)
        if ($session->getRol() == 2 && $idTipoEstado["idcompraestadotipo"] !== 4 && $idTipoEstado["idcompraestadotipo"] !== 3) {
            $estadoActual = $idTipoEstado["idcompraestadotipo"];
            echo "
         <form class='ui form form-cambiar-estado' data-id='{$idCompra}' method='POST'>
            <div class='field'>
            <label>Cambiar estado</label>
            <select class='ui dropdown' name='nuevoestado'>";

            // Mostrar solo la opción siguiente
            if ($estadoActual == 1) {
                echo "<option value='2'>Aceptada</option>";
            } elseif ($estadoActual == 2) {
                echo "<option value='3'>Enviada</option>";
            }
            echo "
            </select>
        </div>
        <input type='hidden' value='{$idCompra}' name='idcompra'>
        <button class='ui primary button w-100' type='submit'>Confirmar cambio</button>
    </form>
    ";
        }

        echo "<div class='ui hidden divider'></div>";
        // Formulario para cancelar compra
        echo "
    <form method='POST' class='ui form form-cancelar-compra mt-3' data-id='{$idCompra}'>
        <input type='hidden' name='idcompracancelar' value='{$idCompra}'>
        <button class='ui red button w-100' type='submit' " . ($idTipoEstado["idcompraestadotipo"] == 4 ||  $idTipoEstado["idcompraestadotipo"] == 3 ? "disabled" : "") . ">Cancelar Compra</button>
    </form>";

        echo "</div></div>";
    }
    ?>
</div>

<script>
    /// Función para manejar el envío de formularios de forma asíncrona
    async function enviarFormulario(event, form, url, mensajeExito, mensajeError, callback) {
        event.preventDefault(); // Prevenir el envío por defecto

        const formData = new FormData(form); // Crear un objeto FormData

        try {
            const response = await fetch(url, {
                method: "POST",
                body: formData,
            });

            const data = await response.json(); // Convertir la respuesta a JSON

            if (data.success) {
                mostrarMensaje("success", mensajeExito);
                if (callback) callback(data); // Ejecutar la función callback si existe
            } else {
                mostrarMensaje("error", mensajeError);
            }
        } catch (error) {
            console.error("Error:", error);
            mostrarMensaje("error", "Ocurrió un error. Intenta nuevamente.");
        }
    }

    // Función para mostrar un mensaje al usuario
    function mostrarMensaje(tipo, mensaje) {
        const header = tipo === "success" ? "Éxito" : "Error";
        $('#mensajeModalHeader').text(header);
        $('#mensajeModalContent').text(mensaje);
        $('#mensajeModal').modal('show');
    }

    // Función para actualizar la interfaz dinámicamente
    function actualizarInterfaz(compraId, nuevoEstadoId, descripcionEstado) {
        const card = document.querySelector(`.ui.raised.segment[data-id="${compraId}"]`);
        if (!card) return;

        // Actualizar el texto del estado
        const estadoText = card.querySelector("span");
        if (estadoText) {
            estadoText.textContent = descripcionEstado;
        }

        // Deshabilitar el botón de cancelar si corresponde
        const botonCancelar = card.querySelector(".form-cancelar-compra button");
        if (botonCancelar) {
            if (nuevoEstadoId == 4) { // 4 = Estado final (cancelado)
                botonCancelar.disabled = true;
            } else {
                botonCancelar.disabled = false;
            }
        }
    }

    // Asociar eventos a los formularios de cambiar estado
    document.querySelectorAll(".form-cambiar-estado").forEach((form) => {
        form.addEventListener("submit", function(event) {
            enviarFormulario(
                event,
                this,
                'Accion/cambiarEstado.php', // URL para cambiar estado
                "Estado cambiado exitosamente.",
                "No se pudo cambiar el estado.",
                (data) => {
                    const compraId = this.dataset.id;
                    const nuevoEstadoId = this.querySelector("[name='nuevoestado']").value;
                    const descripcionEstado = this.querySelector(`[name='nuevoestado'] option[value="${nuevoEstadoId}"]`).textContent;
                    actualizarInterfaz(compraId, nuevoEstadoId, descripcionEstado);
                }
            );
        });
    });

    // Asociar eventos a los formularios de cancelar compra
    document.querySelectorAll(".form-cancelar-compra").forEach((form) => {
        form.addEventListener("submit", function(event) {
            enviarFormulario(
                event,
                this,
                'Accion/cancelarCompra.php', // URL para cancelar compra
                "Compra cancelada exitosamente.",
                "No se pudo cancelar la compra.",
                (data) => {
                    const compraId = this.dataset.id;
                    actualizarInterfaz(compraId, 4, "Cancelada"); // 4 = Estado cancelado
                }
            );
        });
    });
</script>

<?php include_once "../Estructura/footer.php"; ?>