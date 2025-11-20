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
        $usuario = $_SESSION["usuario"];

        $consulta = mysqli_query($conn, "SELECT * FROM pedido WHERE estado = 0");
        $numPedidos = mysqli_num_rows($consulta);
        ?>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="container my-4 flex-grow-1">
            <div class="row justify-content-center">
                <!-- PARTE DE COBRAR -->

                <?php
                $consultaCobrar = "SELECT * FROM pedido WHERE estado = 1";
                $resultadoCobrar = mysqli_query($conn, $consultaCobrar);
                $numPedidosCobrar = mysqli_num_rows($resultadoCobrar);

                ?>
                <div class="col-12 col-lg-8 col-xl-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4 text-center">
                        <header class="text-center mb-3">
                            <?php
                            if ($numPedidosCobrar == 0) {
                                echo "<h4 class='display-6'>Sin cobros pendientes</h4>";
                            } else if ($numPedidosCobrar >= 1) {
                                echo "<h4 class='display-6 mb-5'>Cobros pendientes: $numPedidosCobrar</h4>";
                            }
                            ?>
                        </header>

                        <?php
                        while ($rowCobrar = mysqli_fetch_array($resultadoCobrar)) {
                            $idPedido = $rowCobrar["id"];
                            $mesa = $rowCobrar["numMesa"];

                            echo "<div class='mb-4'>";
                            echo "  <div class='bg-body rounded-4 p-3 p-sm-4 mb-3 pedido'>";
                            echo "      <h5 class='mb-2'>Pedido #$idPedido - Mesa $mesa</h5>";


                        ?>
                            <div class="table-responsive">
                                <table class="table table-hover table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th scope="col">Producto</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Precio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php

                                        $consultaCobrar2 = mysqli_query($conn, "SELECT * FROM producto_pedido WHERE idPedido = '$idPedido'");
                                        $total = 0.0;
                                        while ($producto_pedidoCobrar = mysqli_fetch_array($consultaCobrar2)) {
                                            $idProducto = $producto_pedidoCobrar["idProducto"];
                                            $cantidad = $producto_pedidoCobrar["cant"];

                                            $consultaCobrar3 = mysqli_query($conn, "SELECT * FROM producto WHERE id = '$idProducto'");
                                            $productoCobrar = mysqli_fetch_array($consultaCobrar3);

                                            $nombre = $productoCobrar["nombre"];
                                            $precio = $productoCobrar["precio"];

                                            echo "   <tr>";
                                            echo "      <td>";
                                            echo "          $nombre";
                                            echo "      </td>";
                                            echo "      <td>";
                                            echo "          $cantidad";
                                            echo "      </td>";
                                            echo "      <td>";
                                            echo "          $precio";
                                            echo "      </td>";
                                            echo "   </tr>";
                                            $total += $cantidad * $precio;
                                        }
                                        echo "  <tr>";
                                        echo "      <td colspan='2' class='text-end'>";
                                        echo "          <strong>Total</strong>:";
                                        echo "      </td>";
                                        echo "      <td class='text-start'>";
                                        echo              number_format($total, 2) . " €";
                                        echo "      </td>";
                                        echo "  </tr>";
                                        echo "  <tr>";
                                        echo "  <td colspan='3' class='text-end'>
                                        <a href='imprimirTicket.php?idPedido=$idPedido' class='btn btn-outline-primary me-3'>Imprimir</a>
                                        <button onclick='pagado($idPedido)' class='btn btn-outline-warning'>Cobrar</button></td>";
                                        echo "  </tr>";

                                        ?>
                                    </tbody>
                                </table>
                            </div>



                        <?php



                            echo "  </div>";
                            echo "</div>";
                        }
                        ?>



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

        function pagado(pedido) {
            let confirmar = confirm("¿Estás seguro de poner como pagado el pedido?");

            if (confirmar) {
                // aquí va el location.href para pagar y con ello, liberar la mesa y la reserva
                location.href = "cobrado.php?idPedido=" + pedido;
            }
        }

        function imprimir() {
            // mandamos a imprimir a la impresora
        }
    </script>
</body>
</html>