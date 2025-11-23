<?php
session_start();

include("seguridad.php");

include("../components/conexion.php");
?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>El Quinto Pino - Pedidos</title>
    <link rel="shortcut icon" href="../img/ico.png" type="image/x-icon">
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
        $usuario = $_SESSION["usuario"];

        $consulta = mysqli_query($conn, "SELECT * FROM pedido WHERE estado = 0");
        $numPedidos = mysqli_num_rows($consulta);
        ?>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="container my-4 flex-grow-1">
            <div class="row justify-content-center">
                <!-- PARTE DE SERVIR -->
                <div class="col-12 col-lg-8 col-xl-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4 text-center">
                        <header class="text-center mb-3">
                            <?php
                            if ($numPedidos == 0) {
                                echo "<h4 class='display-6'>Sin pedidos pendientes</h4>";
                            } else if ($numPedidos >= 1) {
                                echo "<h4 class='display-6 mb-5'>Pedidos pendientes: $numPedidos</h4>";
                            }
                            ?>
                        </header>

                        <?php
                        // ordenación ascendent epara que los anteriores sean los primeros
                        $pedidos = mysqli_query($conn, "SELECT * FROM pedido WHERE estado = 0 ORDER BY id ASC");

                        while ($row = mysqli_fetch_array($pedidos)) {
                            $idPedido = $row["id"];
                            $mesa = $row["numMesa"];

                            echo "<div class='mb-4'>";
                            echo "<div class='bg-body rounded-4 p-3 p-sm-4 mb-3 pedido'>";
                            echo "<h5 class='m-0'>Pedido #$idPedido - Mesa $mesa</h5>";



                        ?>
                            <!-- la tabla de los productos del pedido -->
                            <div class="table-responsive">
                                <form action="productoServido.php" method="POST">
                                    <table class="table table-hover table-striped align-middle">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th class="text-center">Cantidad</th>
                                                <th>Comentario</th>
                                                <th class="text-center">Servido</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <?php
                                            $pedidosRes = mysqli_query($conn, "SELECT * FROM producto_pedido WHERE idPedido = '$idPedido'");
                                            $lineasPedido = mysqli_num_rows($pedidosRes); // las líneas que tiene el pedido

                                            while ($infoPedido = mysqli_fetch_array($pedidosRes)) {
                                                $idProducto = $infoPedido["idProducto"];
                                                $cant = $infoPedido["cant"];
                                                $servido = $infoPedido["servido"];
                                                // si hay comentario, cogemos la variable
                                                if (isset($infoPedido["comentario"])) {
                                                    $comentario = $infoPedido["comentario"];
                                                }

                                                // lo que queremos saber del producto
                                                $consultaProducto = mysqli_query($conn, "SELECT * FROM producto WHERE id = '$idProducto'");
                                                $infoProducto = mysqli_fetch_array($consultaProducto);

                                                $nombre = $infoProducto["nombre"];

                                                echo "<tr>";
                                                echo "  <td>$nombre</td>";
                                                echo "  <td>$cant</td>";
                                                if (isset($comentario)) {
                                                    echo "  <td>$comentario</td>";
                                                } else {
                                                    echo "  <td>(Sin comentario)</td>";
                                                }

                                                if ($servido == 1) {
                                                    echo "  <td><input class='form-check-input' type='checkbox' value='$idPedido' name='id' required checked /></td>";
                                                } else {
                                                    echo "  <td><input class='form-check-input' type='checkbox' value='$idPedido' name='id' required /></td>";
                                                }


                                                // prueba02 de envio pedido estado 1, de momento comentada
                                                // if ($servido == 0) {
                                                //     // le paso 2 variables por método get, así es más fácil
                                                //     echo "<td><a href='productoServido.php?idPro=$idProducto&idPed=$idPedido' class='btn btn-outline-warning' required>Servido</a></td>";
                                                // } else {
                                                //     echo "<td><a href='#' class='btn btn-warning disabled'>Servido</a></td>";
                                                // }

                                                echo "</tr>";
                                            }
                                            // esto es de la prueba02
                                            // hago una consulta para verificar las líneas de servido 1, para poder enviar el pedido
                                            // $servidoLineas = mysqli_query($conn, "SELECT servido FROM producto_pedido WHERE servido = 1");
                                            // $contaRows = mysqli_num_rows($servidoLineas);

                                            ?>
                                        </tbody>
                                        <td colspan="2"></td>

                                        <?php
                                        echo "<td colspan='2' class='text-end'><button class='btn btn-warning'>Pedido servido</button></td>"

                                        // prueba02 de envio pedido estado 1, de momento comentada

                                        // if ($contaRows == $lineasPedido) {
                                        // echo "<td colspan='1'><button class='btn btn-warning'>Pedido servido</button></td>";
                                        // } else {
                                        //     echo "<td colspan='1'><button class='btn btn-outline-warning'>Pedido servido</button></td>";
                                        // }

                                        ?>

                                    </table>
                                </form>
                            </div>

                        <?php
                            echo "</div>";
                            echo "</div>";
                        }
                        ?>
                    </section>
                </div>
            </div>
        </main>
    </div>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // de momento no funciona
        function servido() {
            confirm("No se han enviado todos los productos.\n\n¿Estás seguro de marcarlo como servido?")
        }

        function pagado() {
            let confirmar = confirm("¿Estás seguro de poner como pagado el pedido?");

            if (confirmar) {
                // aquí va el location.href para pagar y con ello, liberar la mesa y la reserva
            }
        }

        function imprimir() {
            // mandamos a imprimir a la impresora
        }
    </script>
</body>

</html>