<?php
include_once '../configuracion.php';

$session = new Session;

$data = data_submitted();
// var_dump($data);

$validado = $session->validar();
// if ($validado) {
//     echo "si esta";
// } else {
//     echo "no está";
// }

$title = (array_key_exists("nombrelibro", $data)) ? $data["nombrelibro"] : "Libro";

$controlProducto = new AbmProducto();


if (array_key_exists("id", $data) && $data["id"] != null) {
    $param["idproducto"] = $data["id"];
    $producto = $controlProducto->buscar($param);

    $detallesPro = $producto[0]->getProDetalle();
    $dirImg = "img/Productos/" . $producto[0]->getIdProducto() . ".jpg";

?>
    <!DOCTYPE html>
    <html lang="en">
    <?php include_once "../Estructura/header.php"; ?>

    <div class="ui horizontal divider"></div>
    <div id="contenido-principal" class="ui container grid segment">
        <div class="ui five wide column padded">
            <div id="producto" class="">
                <p>Libros <span class="text black ">/</span> <?= $producto[0]->getProNombre() ?> </p>
                <div>
                    <img src="<?= $dirImg ?>" class="ui large image" alt="<?= $producto[0]->getProNombre() ?>">
                </div>

            </div>
        </div>
        <div id="informacion-producto" class="ui ten wide column">
            <div id="informacion" class="column">
                <h2 class="fs-1"><?= $producto[0]->getProNombre() ?></h2>
                <p class='fs-3 mb-0'>$<?= $producto[0]->getProPrecio() ?></p>
                <p class="ui green label"><i class="check icon"></i> Envio Gratis</p>
                <!-- ¿HAY STOCK? -->
                <?php if ($producto[0]->getProCantStock() > 0) { ?>
                    <p class="ui green label"><i class="check icon"></i> Hay <?= $producto[0]->getProCantStock(); ?> productos en stock </p>
                <?php } else { ?>
                    <p class="ui red label"><i class="times icon"></i> No hay stock </p>
                <?php } ?>
                <?php if ($producto[0]->getProCantStock() > 0) { ?>
                    <div class="ui horizontal divider"></div>
                    <form action="carritoCompra.php" method="POST" class="ui form">
                        <input type="number" class="cantidad" name="cantidad" min="1" max="<?= $producto[0]->getProCantStock() ?>" required value="1">
                        <input type="hidden" class="idproducto" name="idproducto" value="<?= $producto[0]->getIdProducto(); ?>">

                        <div class="ui basic segment center aligned">
                            <button type="submit" <?php if (!$session->validar()) { ?> disabled <?php } ?> class="ui primary button">Agregar al Carrito</button>
                        </div>
                    </form>
                <?php } ?>
                <hr>
            </div>
            <div id="producto-descripcion" class="ui segment">
                <div>
                    <p class="ui header">Descripción</p>
                    <p><?= $detallesPro ?></p>
                </div>
            </div>
        </div>
    </div>



<?php } else { ?>
    <p class='container fs-3 mt-5'>No se encontro el libro buscado.</p>
<?php } ?>

<?php include_once "../Estructura/footer.php"; ?>