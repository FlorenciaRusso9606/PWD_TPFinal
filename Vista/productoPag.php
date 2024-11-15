<?php
include_once '../configuracion.php';

$session = new Session;

$data = data_submitted();

$title = (array_key_exists("nombrelibro", $data)) ? $data["nombrelibro"] : "Libro";

$controlProducto = new AbmProducto();


if (array_key_exists("id", $data) && $data["id"] != null) {
    $param["idproducto"] = $data["id"];
    $producto = $controlProducto->buscar($param);

    $detallesPro = $producto[0]->getProDetalle();
    $dirImg = "img/Productos/" . $producto[0]->getProPrecio() . ".jpg";
?>
<!DOCTYPE html>
<html lang="en">
<?php include_once "../Estructura/header.php";?>
    <div id="contenido-principal" class="container mt-5 d-flex border">
        <div class="w-100 m-3">
            <div id="producto" class="w-75 me-5">
                <p>Libros <span class="text-black-50">/</span> <?= $producto[0]->getProNombre() ?> </p>
                <div class="">
                    <img src="<?= $dirImg ?>" class="d-block w-100" alt=<?= $producto[0]->getProNombre() ?>">
                </div>

            </div>
        </div>
        <div id="informacion-producto" class="ms-5 d-flex flex-column w-100 justify-content-start align-items-start ">
            <div id="informacion" class="w-50">
                <h2 class="fs-1"><?= $producto[0]->getProNombre() ?></h2>
                <p class='fs-3 mb-0'>$<?= $producto[0]->getProPrecio() ?></p>
                <div class="d-flex">
                    <p class="me-5 text-success text-nowrap"><i class="fas fa-check fa-xs text-success"></i> Envio Gratis</p>
                    <!-- ¿HAY STOCK? -->
                    <?php if ($producto[0]->getProCantStock() > 0) { ?>
                        <p class="text-success text-nowrap"><i class="fas fa-check fa-xs text-success"></i> Hay stock </p>
                    <?php } else { ?>
                        <p class="text-danger text-nowrap"><i class="fas fa-times fa-xs text-danger"></i> No hay stock </p>
                    <?php } ?>
                </div>
                <?php if ($producto[0]->getProCantStock() > 0) { ?>
                    <form action="carritoCompra.php" method="POST">
                        <input type="number" class="hide" value="<?= $producto[0]->getIdProducto(); ?>" name="idproducto">
                        <button type="submit" <?php if ($session->validar()) { ?> disabled <?php } ?> class="btn btn-primary mt-4">Agregar al Carrito</button>
                    </form>
                <?php } ?>
                <hr>
            </div>
            <div id="producto-descripcion" class="my-5 w-100">
                <div>
                    <p class="uppercase">Descripción</p>
                    <p><?= $detallesPro ?></p>
                </div>
            </div>
        </div>
    </div>


<?php } else { ?>
    <p class='container fs-3 mt-5'>No se encontro el libro buscado.</p>
<?php } ?>

<?php include_once "../Estructura/footer.php"; ?>