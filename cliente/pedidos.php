<?php
session_start();
include("../components/conexion.php");

include("seguridad.php");
$dni = $_SESSION["dni"];
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
        ?>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="container my-4 flex-grow-1">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4">
                        <header class="justify-content-between align-items-center mb-3">
                            <h2 class="display-6 m-0 text-center flex-grow-1">Pedidos</h2>
                        </header>
                        <?php
                        // traemos todos los pedidos del usuario
                        $pedidos = mysqli_query($conn, "SELECT * FROM pedido WHERE usuario = '$dni' ORDER BY id DESC");


                        // recorremos cada pedido
                        while ($rowPedido = mysqli_fetch_array($pedidos)) {
                            $idPedido = $rowPedido["id"];
                            $estado = $rowPedido["estado"];
                            $mesa = $rowPedido["numMesa"];

                            print("<div class='mb-4'>");
                            print("<div class='bg-body rounded-4 p-3 p-sm-4 mb-3'>");
                            print("<div class='d-flex justify-content-between align-items-center mb-2'>");
                            print("<h5 class='m-0'>Pedido #$idPedido</h5>");
                            if ($estado == 2) {
                                print("<span class='badge text-bg-warning'>Pagado</span>");
                            } else if ($estado == 1) {
                                print("<span class='badge text-bg-success'>Enviado</span>");
                            } else {
                                print("<span class='badge text-bg-secondary'>Pendiente</span>");
                            }
                            print("</div>");

                            print("<p class='small text-muted m-0 mb-2'><i>Mesa $mesa</i></p>");

                            // Traemos las líneas del pedido
                            $lineas = mysqli_query($conn, "SELECT * FROM producto_pedido WHERE idPedido = '$idPedido'");

                            $totalPedido = 0.0;

                            // creamos la tabla
                            print("<div class='table-responsive'>");
                            print("<table class='table table-hover table-striped align-middle'>");
                            print("<thead>");
                            print("<tr>");
                            print("<th>Producto</th>");
                            print("<th class='text-center'>Precio</th>");
                            print("<th class='text-center'>Cantidad</th>");
                            print("<th>Comentario</th>");
                            print("<th class='text-center'>Subtotal</th>");
                            print("</tr>");
                            print("</thead>");
                            print("<tbody>");

                            while ($rowLinea = mysqli_fetch_array($lineas)) {
                                $idProducto = $rowLinea["idProducto"];
                                $cant = $rowLinea["cant"];
                                $comentario = "";

                                if (isset($rowLinea["comentario"])) {
                                    $comentario = $rowLinea["comentario"];
                                }

                                // Traemos nombre y precio del producto
                                $prodRes = mysqli_query($conn, "SELECT nombre, precio FROM producto WHERE id = '$idProducto'");
                                if ($prodRes) {
                                    $prod = mysqli_fetch_array($prodRes);
                                    if ($prod) {
                                        $nombre = $prod["nombre"];
                                        $precio = $prod["precio"];
                                    } else {
                                        $nombre = "Producto $idProducto";
                                        $precio = 0;
                                    }
                                } else {
                                    $nombre = "Producto $idProducto";
                                    $precio = 0;
                                }

                                $subtotal = $precio * $cant;
                                $totalPedido = $totalPedido + $subtotal;

                                print("<tr>");
                                print("<td>" . $nombre . "</td>");
                                print("<td class='text-center'>" . number_format($precio, 2) . " €</td>");
                                print("<td class='text-center'>$cant</td>");

                                if ($comentario != "") {
                                    print("<td>" . $comentario . "</td>");
                                } else {
                                    print("<td><span class='text-muted'></span></td>");
                                }

                                print("<td class='text-center'>" . number_format($subtotal, 2) . " €</td>");
                                print("</tr>");
                            }
                            print("<tr>");
                            print("<td colspan='4' class='text-end'>Total</td>");
                            print("<td class='text-center'>" . number_format($totalPedido, 2) . " €</td>");
                            print("</tr>");
                            print("</tbody>");
                            print("</table>");
                            print("</div>");
                            print("</div>");
                        }


                        print("<hr class='mt-3'>");
                        print("</div>");
                        ?>
                    </section>
                </div>
            </div>
        </main>
    </div>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>