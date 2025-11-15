<?php
session_start();

include("../components/conexion.php");
include("seguridad.php");

if (!isset($_SESSION["haElegidoMesa"])) {
    header("LOCATION: mesa.php");
} else if (!isset($_SESSION["haElegidoComensales"])) {
    header("LOCATION: eligeComensales.php");
}

// VARIABLES DE SESIÓN
$usuario = $_SESSION["usuario"];
$dni = $_SESSION["dni"];

// si el usuario cierra sesión y vuelve a entrar
if (!isset($_SESSION["mesa"])) {
    $consulta = mysqli_query($conn, "SELECT * FROM reserva WHERE dni = '$dni'");
    $row = mysqli_fetch_array($consulta);
    $_SESSION["mesa"] = $row["numMesa"];
} else {
    $mesa = $_SESSION["mesa"];
}



$result = mysqli_query($conn, "SELECT * FROM reserva WHERE dni = '$dni'");
$row = mysqli_fetch_assoc($result);

$mesa = $row["numMesa"];
$comensales = $row["comensales"];

// si el carrito NO está inicializado, lo inicializamos
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

// agregamos la ID al carrito y recargamos la página
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if (!isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id] = 0;
    }
    $_SESSION['carrito'][$id] += 1;
    header("LOCATION: carta.php");
    exit;
}
/*
    BUG 1: los pedidos no se actualizan correctamtne - OK
    BUG 2: las cantidades de los pedidos?? donde están? - OK
    BUG 3: es NECESARIO que los comentarios vayan por producto - OK
    BUG 4: las inserciones no insertan - OK
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // si le damos a pedir, realizamos el insert en la base de datos
    if (isset($_POST["pedir"])) {
        // si vienen cantidades en el post, sincronizamos primero
        if (isset($_POST['cantidades'])) {
            foreach ($_POST['cantidades'] as $id => $cantidad) {
                $_SESSION['carrito'][$id] = $cantidad;
            }
        }

        // por si el usuario sale de la sesión, evitamos que pierda el pedido asignado
        if (!isset($_SESSION['idPedido'])) {
            $pedido = mysqli_query($conn, "SELECT * FROM pedido WHERE usuario = (SELECT dni FROM reserva WHERE dni = '$dni')");

            $rowPedido = mysqli_num_rows($pedido);
            // si ya existe un pedido con ese DNI y esa MESA
            if ($rowPedido > 0) {
                $rowPedido = mysqli_fetch_array($pedido);
                $idPedido = $rowPedido["id"];
                $_SESSION["idPedido"] = $idPedido;
            } else {
                $pedido = "INSERT INTO pedido (usuario, estado, numMesa)
                        VALUES('$dni', 0, '$mesa')";

                mysqli_query($conn, $pedido);

                // guardamos el id del pedido en la sesión
                $_SESSION['idPedido'] = mysqli_insert_id($conn);
                $idPedido = $_SESSION["idPedido"];
            }
        } else {
            $idPedido = $_SESSION["idPedido"];
        }



        // se realiza el insert de los productos del pedido
        foreach ($_SESSION['carrito'] as $idProducto => $cant) {
            // cantidad final tomada del formulario
            if (isset($_POST['cantidades'][$idProducto])) {
                $cant = $_POST['cantidades'][$idProducto];
            }

            // comentario por producto, IMPORTANTE!!!!!!!
            $coment = "";
            if (isset($_POST["comentario"][$idProducto]) && $_POST["comentario"][$idProducto] != "") {
                $coment = $_POST["comentario"][$idProducto];
            }

            /*
            esta consulta es gracias a chatgpt;
            LO QUE HACE: si hay 1 pedido y 1 producto en el mismo pedido; le suma la cantidad al producto.
            (mucho mejor que la opción de abajo comentada)
            */
            $productoPedido = " INSERT INTO producto_pedido (idPedido, idProducto, cant, comentario)
                                VALUES ('$idPedido', '$idProducto', '$cant', '$coment')
                                    ON DUPLICATE KEY UPDATE
                                        cant = cant + VALUES(cant),
                                        comentario = VALUES(comentario)";

            $updateEstado = mysqli_query($conn, "UPDATE pedido SET estado = 0 WHERE id   = '$idPedido'");

            // reemplazo por la IA
            // if ($coment == "") {
            //     $productoPedido = "INSERT INTO producto_pedido (idPedido, idProducto, cant)
            //                        VALUES ('$idPedido', '$idProducto', '$cant')";
            // } else {
            //     $productoPedido = "INSERT INTO producto_pedido (idPedido, idProducto, cant, comentario)
            //                        VALUES ('$idPedido', '$idProducto', '$cant', '$coment')";
            // }

            // restamos el stock de la cantidad
            $stock = mysqli_query($conn, "SELECT stock FROM producto WHERE id = $idProducto");
            $rowStock = mysqli_fetch_array($stock);
            $stock = $rowStock["stock"] - $cant;
            mysqli_query($conn, "UPDATE producto SET stock = '$stock' WHERE id = '$idProducto'");

            mysqli_query($conn, $productoPedido);
        }

        // por último limpiamos el carrito de la sesión
        $_SESSION['carrito'] = [];

        header("LOCATION: pedidos.php");
        exit;
    }

    // actualizamos las cantidades cuando pulsamos varias veces en 'Añadir' o el botón de actualizar
    if (isset($_POST["cantidades"])) {
        foreach ($_POST['cantidades'] as $id => $cantidad) {
            $_SESSION['carrito'][$id] = $cantidad;
        }
        header("LOCATION: carta.php");
        exit;
    }
}

// si eliminamos UN ÚNICO producto en la lista de pedidos
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['carrito'][$id]);
    header("LOCATION: carta.php");
    exit;
}

// si limpiamos TODOS los productos en la lista de pedidos
if (isset($_GET['limpiar'])) {
    $_SESSION['carrito'] = [];
    header("LOCATION: carta.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>El Quinto Pino - Cliente</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="../styles.css" />
</head>

<body>
    <div class="container-fluid min-vh-100 d-flex flex-column">
        <!-- HEADER + NAV -->
        <?php
        include("../components/header.php");
        include("navbar.php");

        $consulta = "SELECT * FROM usuario WHERE dni = '$dni'";
        $result = mysqli_query($conn, $consulta);
        $row = mysqli_fetch_array($result);
        ?>
        <main class="container my-4 flex-grow-1">
            <!-- CARTA -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-12 col-xxl-5">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4">
                        <header class="justify-content-between align-items-center mb-3">
                            <h2 class="display-6 m-0 text-center flex-grow-1">Carta</h2>
                        </header>

                        <!-- FILTRO POR CATEGORÍA -->
                        <form method="get" action="" class="mb-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-8">
                                    <select name="categoria" class="form-select">
                                        <?php
                                        $categorias = mysqli_query($conn, "SELECT * FROM categoria");
                                        while ($cat = mysqli_fetch_array($categorias)) {
                                            $idCat = $cat["id"];
                                            $nombreCat = $cat["nombre"];
                                            if (isset($_GET["categoria"]) && $_GET["categoria"] == $idCat) {
                                                print("<option value='$idCat' selected>$nombreCat</option>");
                                            } else {
                                                print("<option value='$idCat'>$nombreCat</option>");
                                            }
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-4 text-end">
                                    <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                                </div>
                            </div>
                        </form>

                        <div class="col-12 table-responsive align-items-center">
                            <table class="table text-start text-md-center table-hover table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th class='d-none d-md-table-cell'>Imagen</th>
                                        <th>Nombre</th>
                                        <th>Precio</th>
                                        <th>Categoria</th>
                                        <th>Selección</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // filtrado por categoria
                                    if (isset($_GET["categoria"])) {
                                        $cat = $_GET["categoria"];
                                        $consulta = "SELECT * FROM producto WHERE categoria = '$cat'";
                                        $result = mysqli_query($conn, $consulta);
                                    } else {
                                        $consulta = "SELECT * FROM producto";
                                        $result = mysqli_query($conn, $consulta);
                                    }

                                    // realizo una tabla con todos los productos de la base de datos.
                                    while ($row = mysqli_fetch_array($result)) {
                                        $id = $row['id'];
                                        $estado = $row['estado'];
                                        $estadoCategoria = $row['estado_categoria'];
                                        $stock = $row['stock'];
                                        $imagen = $row['imagen'];

                                        if ($estado == 1 && $stock > 0 && $estadoCategoria == 1) { // solo ponemos en la carta los disponibles.
                                            print("<tr>");
                                            print("<td class='d-none d-md-table-cell'>");
                                            print("<img src='$imagen' width='50px' class='img-fluid'>");
                                            print("</td>");
                                            print("<td>");
                                            print($row['nombre']);
                                            print("</td>");
                                            print("<td>");
                                            print($row['precio']);
                                            print("</td>");
                                            print("<td>");

                                            $cat = $row["categoria"];
                                            $consulta2 = "SELECT nombre FROM categoria WHERE id = '$cat'";
                                            $result2 = mysqli_query($conn, $consulta2);
                                            $row2 = mysqli_fetch_array($result2);
                                            print($row2["nombre"]);

                                            print("</td>");
                                            print("<td>");
                                            print("<a href='carta.php?id=$id' class='btn btn-primary'>Añadir</a>");
                                            print("</td>");
                                            print("</tr>");
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <!-- PRODUCTOS -->
                <div class="col-12 col-md-12 order-first order-xxl-2 mb-3 col-xxl-7">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4">
                        <header class="justify-content-between align-items-center">
                            <h2 class="display-6 m-0 text-center flex-grow-1">Pedido</h2>
                            <p class="text-center text-muted small"><i>Mesa <?php echo $mesa ?> - <?php echo $comensales ?> comensales</i></p>
                        </header>

                        <form action="" method="post">
                            <?php if (empty($_SESSION['carrito'])) { ?>
                                <p class="text-center text-muted">No hay productos en el pedido.</p>
                            <?php } else { ?>
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped align-middle align-items-center">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th class="text-center">Precio</th>
                                                <th class="text-center">Cantidad</th>
                                                <th class="text-center">Comentario</th>
                                                <th class="text-center">Subtotal</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $total = 0.0;
                                            foreach ($_SESSION["carrito"] as $idProd => $cant) {
                                                // se recupera nombre y precio del producto
                                                $consulta2 = mysqli_query($conn, "SELECT nombre, precio FROM producto WHERE id = $idProd");
                                                $producto = mysqli_fetch_assoc($consulta2);

                                                $nombre = $producto['nombre'];
                                                $precio = $producto['precio'];
                                                $subtotal = $precio * $cant;
                                                $total += $subtotal;

                                                echo "<tr>";
                                                echo "<td>$nombre</td>";
                                                echo "<td class='text-center'>" . number_format($precio, 2) . "€</td>";
                                                echo "<td class='text-center' style='max-width:120px;'>";

                                                // para el máximo y evitar que no puedan pedir más del stock que haya:
                                                $consulta = mysqli_query($conn, "SELECT stock FROM producto WHERE id = '$idProd'");
                                                $rowStock = mysqli_fetch_array($consulta);
                                            ?>
                                                <input type="number"
                                                    name="cantidades[<?php echo $idProd ?>]"
                                                    value="<?php echo $cant ?>"
                                                    min="1" max="<?php echo $rowStock["stock"] ?>"
                                                    class="form-control text-center">

                                                <small class="small text-muted text-start">Máx: <?php echo $rowStock["stock"] ?></small>
                                                <?php
                                                echo "</td>";
                                                echo "<td>";
                                                ?>
                                                <textarea
                                                    name="comentario[<?php echo $idProd; ?>]"
                                                    class="form-control text-center" maxlength="15"
                                                    placeholder="Comentario... (Opcional)"></textarea>
                                            <?php
                                                echo "</td>";
                                                echo "<td class='text-center'> " . number_format($subtotal, 2) . " €</td>";
                                                echo "<td class='text-center'>";
                                                echo "<a class='btn btn-sm btn-outline-danger' href='carta.php?remove=$idProd'>Eliminar</a>";
                                                echo "</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" class="text-end">Total</th>
                                                <th class="text-center"><?php echo number_format($total, 2) ?> €</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>


                                <div class="d-flex gap-2 justify-content-end">
                                    <a class="btn btn-outline-secondary" href="carta.php?limpiar=1">Vaciar</a>
                                    <button type="submit" class="btn btn-outline-primary">Actualizar cantidades</button>
                                    <button type="submit" name="pedir" value="1" class="btn btn-success">Pedir</button>
                                </div>
                            <?php } ?>
                        </form>
                    </section>
                </div>
            </div>
        </main>
    </div>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>