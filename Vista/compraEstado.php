<?php
include_once "../configuracion.php";

$data = data_submitted();
$session = new Session;
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once "../Estructura/header.php"; ?>

<h2 class="container my-5 text-center">Estado de Compra</h2>
<div class="card my-4 shadow-sm" data-id="<?= $idCompra ?>">
    <!-- Contenido de la tarjeta -->
</div>

<div class="container">
<?php
$message = '';


if (isset($data["resp"])) {
    if (isset($data['message']) && $data['message'] === 'cancelarcompra') {
        $message = $data["resp"] === "success" ? "Compra cancelada exitosamente" : "No se pudo cancelar su compra";
    }
    else if (isset($data['message']) && $data['message'] === 'cambiarestado') {
        $message = $data["resp"] === "success" ? "Estado de compra cambiado exitosamente" : "No se pudo cambiar el estado de su compra";
    }
  }
    $alertClass = $data["resp"] === "success" ? "alert-success" : "alert-danger";
    echo "<div class='alert $alertClass text-center'>$message</div>";

$abmEstadoTipo = new ABMcompraEstadoTipo;
$abmCompraItem = new AbmCompraItem();
$controlCompra = new ControlCompra;
$compras = $controlCompra->buscarCompras($session);
$abmEstadoTipo = new ABMcompraEstadoTipo();
$abmCompraItem = new AbmCompraItem();
$ambCompraEstado = new AbmCompraEstado();

foreach ($compras as $compra) {
    $idCompra = $compra->getIdCompra();
    $paramIdCompra = ["idcompra" => $idCompra];
    
    // Obtener estado
    $estado = $ambCompraEstado->buscar($paramIdCompra);
    if (!$estado) {
        continue;
    }
    
    // Obtener items de la compra
    $arrItems = $abmCompraItem->buscar($paramIdCompra);

    // Mostrar información de la compra
    echo "<div class='card my-4 shadow-sm'>";
    echo "<div class='card-body'>";
    echo "<h5 class='card-title'>Compra ID: $idCompra</h5>";
    $idTipoEstado["idcompraestadotipo"] = $estado[0]->getobjCompraEstadoTipo()->getidcompraestadotipo();
    echo "<p class='card-text'><strong>Estado:</strong> {$estado[0]->getobjCompraEstadoTipo()->getCetDescripcion()}</p>";
    
    $precioTotal = 0;
    echo "<ul class='list-group list-group-flush mb-3'>";
    foreach ($arrItems as $item) {
        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
        echo "<span>{$item->getobjProducto()->getProNombre()} <small>(Cantidad: {$item->getCiCantidad()})</small></span>";
        $precioTotal += $item->getObjProducto()->getProPrecio();
    }
    echo "</ul>";
    echo "<p class='card-text'><strong>Total:</strong> $$precioTotal</p>";

    if ($session->getRol() == 2 && $idTipoEstado["idcompraestadotipo"] != 4) {
      
        echo "
        <form class='form-cambiar-estado' data-id='{$idCompra}' action='accion/cambiarEstado.php' method='POST'>
            <div class='mb-3'>
                <select class='form-select' name='nuevoestado'>
                    <option selected>Cambiar estado</option>
                    <option value='1'>Iniciada</option>
                    <option value='2'>Aceptada</option>
                    <option value='3'>Enviada</option>
                </select>
            </div>
            <input type='hidden' value='{$idCompra}' name='idcompra'>
            <button class='btn btn-primary w-100' type='submit'>Confirmar cambio</button>
        </form>
        ";
    }

    // Formulario para cancelar compra
    echo "
    <form method='POST' action='accion/cancelarCompra.php' class='form-cancelar-compra mt-3' data-id='{$idCompra}'>
        <input type='hidden' name='idcompracancelar' value='{$idCompra}'>
        <button class='btn btn-danger w-100' type='submit' " . ($idTipoEstado["idcompraestadotipo"] == 4 ? "disabled" : "") . ">Cancelar Compra</button>
    </form>";

    echo "</div></div>";}
?>
</div>



<!--<script type="text/javascript">
  /// Función para manejar el envío de formularios de forma asíncrona
async function enviarFormulario(event, form, mensajeExito, mensajeError, callback) {
    event.preventDefault(); // Prevenir el envío por defecto

    const formData = new FormData(form); // Crear un objeto FormData
    const action = form.getAttribute("action"); // Obtener la acción del formulario

    try {
        const response = await fetch(action, {
            method: "POST",
            body: formData,
        });

        const data = await response.json(); // Convertir la respuesta a JSON

        if (data.success) {
            mostrarMensaje("success", mensajeExito);
            if (callback) callback(data); // Ejecutar la función callback si existe
        } else {
            mostrarMensaje("danger", mensajeError);
        }
    } catch (error) {
        console.error("Error:", error);
        mostrarMensaje("danger", "Ocurrió un error. Intenta nuevamente.");
    }
}

// Función para mostrar un mensaje al usuario
function mostrarMensaje(tipo, mensaje) {
    let alertContainer = document.querySelector("#alert-container");
    if (!alertContainer) {
        alertContainer = document.createElement("div");
        alertContainer.id = "alert-container";
        alertContainer.className = "my-3";
        document.querySelector("body").prepend(alertContainer);
    }

    const alertDiv = document.createElement("div");
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.setAttribute("role", "alert");
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    alertContainer.appendChild(alertDiv);
    setTimeout(() => alertDiv.remove(), 5000); // Eliminar después de 5 segundos
}

// Función para actualizar la interfaz dinámicamente
function actualizarInterfaz(compraId, nuevoEstadoId, descripcionEstado) {
    const card = document.querySelector(`.card[data-id="${compraId}"]`);
    if (!card) return;

    // Actualizar el texto del estado
    const estadoText = card.querySelector(".card-text strong + span");
    if (estadoText) {
        estadoText.textContent = ` ${descripcionEstado}`;
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
    form.addEventListener("submit", function (event) {
        enviarFormulario(
            event,
            this,
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
    form.addEventListener("submit", function (event) {
        enviarFormulario(
            event,
            this,
            "Compra cancelada exitosamente.",
            "No se pudo cancelar la compra.",
            (data) => {
                const compraId = this.dataset.id;
                actualizarInterfaz(compraId, 4, "Cancelada"); // 4 = Estado cancelado
            }
        );
    });
});


</script>!-->

<?php include_once "../Estructura/footer.php"; ?>
