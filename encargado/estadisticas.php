<?php
session_start();

include("../components/conexion.php");

include("seguridad.php");

?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>El Quinto Pino - Estadísticas</title>
    <link rel="shortcut icon" href="../img/ico.png" type="image/x-icon">
    <link rel="stylesheet" href="../bootstrap/css/mdb.min.css">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                <div class="col-12">
                    <section class="bg-dark-subtle border rounded-4 p-4 p-sm-4">
                        <header class="text-center mb-3">
                            <h4 class="display-6 mb-1">Estadísticas</h4>
                        </header>
                        <section class="row justify-content-center ">
                            <div class="col-12 table-responsive">

                                <?php
                                // cogemos el total de comensales
                                $consultaComensales = "SELECT SUM(comensales) as suma FROM pedido";
                                $resultComensales = mysqli_query($conn, $consultaComensales);
                                $rowComensales = mysqli_fetch_array($resultComensales);
                                $totalComensales = $rowComensales["suma"];

                                // si está vacio ponemos 0
                                if ($totalComensales == "") {
                                    $totalComensales = 0;
                                }

                                $cajaTotal = 0;

                                // seleccionamos todos los pedidos pagados
                                $consultaPedidosPagados = "SELECT id FROM pedido WHERE estado = 2";
                                $resultPedidosPagados = mysqli_query($conn, $consultaPedidosPagados);

                                while ($rowPedido = mysqli_fetch_array($resultPedidosPagados)) {
                                    $idPedido = $rowPedido['id'];

                                    // hago un selec tde los productos del pedido
                                    $consultaProductosPedido = "SELECT idProducto, cant FROM producto_pedido WHERE idPedido = '$idPedido'";
                                    $resultProductosPedido = mysqli_query($conn, $consultaProductosPedido);

                                    while ($rowProdPed = mysqli_fetch_array($resultProductosPedido)) {
                                        $idProducto = $rowProdPed['idProducto'];
                                        $cant = $rowProdPed['cant'];

                                        // necesario el precio para calcular las medias  y los totales
                                        $consultaProducto = "SELECT precio FROM producto WHERE id = '$idProducto'";
                                        $resultProducto = mysqli_query($conn, $consultaProducto);
                                        $rowProducto = mysqli_fetch_array($resultProducto);
                                        $precio = $rowProducto['precio'];

                                        // sumamos al total
                                        $cajaTotal += $cant * $precio;
                                    }
                                }

                                // otro select del pedido para sacar los comensales y la cuenta de los pedidos pagados
                                $consultaPedidos = "SELECT COUNT(id) as numPedidos, SUM(comensales) as numComensales
                                                   FROM pedido 
                                                    WHERE estado = 2";
                                $resultPedidos = mysqli_query($conn, $consultaPedidos);
                                $rowPedidos = mysqli_fetch_array($resultPedidos);
                                $numPedidosPagados = $rowPedidos["numPedidos"];
                                $comensalesPagados = $rowPedidos["numComensales"];

                                if ($numPedidosPagados == "") {
                                    $numPedidosPagados = 0;
                                }
                                if ($comensalesPagados == "") {
                                    $comensalesPagados = 0;
                                }

                                // se soluciona problema por dividir entre 0
                                if ($numPedidosPagados > 0) {
                                    $ticketMedio = $cajaTotal / $numPedidosPagados;
                                } else {
                                    $ticketMedio = 0;
                                }

                                // se soluciona problema por dividir entre 0
                                if ($comensalesPagados > 0) {
                                    $gastoPorComensal = $cajaTotal / $comensalesPagados;
                                } else {
                                    $gastoPorComensal = 0;
                                }
                                ?>

                                <div class="row g-4">
                                    <!-- total comensales -->
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="card bg-primary text-white h-100">
                                            <div class="card-body text-center">
                                                <h5 class="card-title"><i class="fas fa-users fa-2x mb-3"></i><br>Total Comensales</h5>
                                                <p class="card-text display-6 fw-bold">
                                                    <?php print($totalComensales); ?>
                                                </p>
                                                <small>Histórico completo</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- caja total -->
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="card bg-success text-white h-100">
                                            <div class="card-body text-center">
                                                <h5 class="card-title"><i class="fas fa-euro-sign fa-2x mb-3"></i><br>Caja Total</h5>
                                                <p class="card-text display-6 fw-bold">
                                                    <?php print(number_format($cajaTotal, 2)); ?> €
                                                </p>
                                                <small>Pedidos pagados</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ticket medio -->
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="card bg-warning text-white h-100">
                                            <div class="card-body text-center">
                                                <h5 class="card-title"><i class="fas fa-receipt fa-2x mb-3"></i><br>Ticket Medio</h5>
                                                <p class="card-text display-6 fw-bold">
                                                    <?php print(number_format($ticketMedio, 2)); ?> €
                                                </p>
                                                <small>Por pedido pagado</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- gasto medio por comensal -->
                                    <div class="col-12 col-md-6 col-xl-3">
                                        <div class="card bg-info text-white h-100">
                                            <div class="card-body text-center">
                                                <h5 class="card-title"><i class="fas fa-user-tag fa-2x mb-3"></i><br>Gasto / Comensal</h5>
                                                <p class="card-text display-6 fw-bold">
                                                    <?php print(number_format($gastoPorComensal, 2)); ?> €
                                                </p>
                                                <small>En pedidos pagados</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- top 3 productos más vendidos -->
                                <div class="row g-4 mb-4 mt-4">
                                    <?php
                                    // obtener productos más vendidos
                                    $consulta = "SELECT idProducto, SUM(cant) as total FROM producto_pedido WHERE idProducto > 0 GROUP BY idProducto ORDER BY total DESC";
                                    $resultado = mysqli_query($conn, $consulta);

                                    $posicion = 0;
                                    while ($row = mysqli_fetch_array($resultado)) {
                                        if ($posicion >= 3) {
                                            break;
                                        }

                                        $idProducto = $row['idProducto'];
                                        $cantidad = $row['total'];

                                        // obtener nombre del producto
                                        $consultaNombre = "SELECT nombre FROM producto WHERE id = '$idProducto'";
                                        $resultNombre = mysqli_query($conn, $consultaNombre);
                                        $rowNombre = mysqli_fetch_array($resultNombre);


                                        $nombre = $rowNombre['nombre'];
                                        $numero = $posicion + 1;
                                        if ($posicion == 0) {
                                            $color = "bg-primary";
                                        } elseif ($posicion == 1) {
                                            $color = "bg-warning";
                                        } else {
                                            $color = "bg-danger";
                                        }
                                    ?>
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="card <?php echo $color; ?> text-white h-100">
                                                <div class="card-body text-center">
                                                    <h5 class="card-title">
                                                        <i class="fas fa-medal fa-2x mb-3"></i><br>
                                                        Nº <?php echo $numero; ?>
                                                    </h5>
                                                    <p class="card-text display-6 fw-bold">
                                                        <?php echo $nombre; ?>
                                                    </p>
                                                    <p class="card-text">
                                                        Vendidos: <strong><?php echo number_format($cantidad, 0); ?></strong>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                        $posicion++;
                                    }

                                    ?>
                                </div>

                                <!-- ESTADÍSTICAS -->
                                <div class="row mb-4 mt-4">
                                    <div class="col-12">
                                        <div class="card bg-dark text-white">
                                            <div class="card-body">
                                                <h5 class="card-title mb-4">Estadísticas por día</h5>

                                                <!-- fecha -->
                                                <form action="estadisticas.php" method="GET" class="row g-3 mb-4 align-items-end">
                                                    <div class="col-md-5">
                                                        <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                                        <input type="date" class="form-control bg-dark text-white" id="fecha_inicio" name="fecha_inicio" value="<?php echo isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : date('Y-m-01'); ?>">
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                                        <input type="date" class="form-control bg-dark text-white" id="fecha_fin" name="fecha_fin" value="<?php echo isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : date('Y-m-d'); ?>">
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="submit" class="btn btn-primary w-100">
                                                            <i class="fas fa-filter me-2"></i>Filtrar
                                                        </button>
                                                    </div>
                                                </form>

                                                <!-- yabla de estadísticas -->
                                                <div class="table-responsive">
                                                    <table class="table table-dark table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>Fecha</th>
                                                                <th>Comensales</th>
                                                                <th>Productos vendidos</th>
                                                                <th>Facturación</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php
                                                            // obtener las fechas del filtro o por defecto el mes actual
                                                            if (isset($_GET['fecha_inicio'])) {
                                                                $fechaInicio = $_GET['fecha_inicio'];
                                                            } else {
                                                                $fechaInicio = date('Y-m-01');
                                                            }

                                                            if (isset($_GET['fecha_fin'])) {
                                                                $fechaFin = $_GET['fecha_fin'];
                                                            } else {
                                                                $fechaFin = date('Y-m-d');
                                                            }

                                                            // selecciono las fechas únicas en el rango
                                                            $consultaFechas = "SELECT DISTINCT DATE(fecha) as fecha FROM pedido WHERE DATE(fecha) >= '$fechaInicio' AND DATE(fecha) <= '$fechaFin' ORDER BY fecha DESC";
                                                            $resultFechas = mysqli_query($conn, $consultaFechas);

                                                            while ($rowFecha = mysqli_fetch_array($resultFechas)) {
                                                                $fecha = $rowFecha['fecha'];

                                                                // cojo el total de comensales de ese día
                                                                $consultaComensalesDia = "SELECT SUM(comensales) as suma FROM pedido WHERE DATE(fecha) = '$fecha'";
                                                                $resultComensalesDia = mysqli_query($conn, $consultaComensalesDia);
                                                                $rowComensalesDia = mysqli_fetch_array($resultComensalesDia);
                                                                $totalComensalesDia = $rowComensalesDia["suma"] ? (int)$rowComensalesDia["suma"] : 0;

                                                                // aquí guardo los totales, inicializados a 0
                                                                $totalProductosDia = 0;
                                                                $totalCajaDia = 0;

                                                                // selecciono los productos de la fecha que esté seleccionada
                                                                $consultaPedidosDia = "SELECT id FROM pedido WHERE DATE(fecha) = '$fecha'";
                                                                $resultPedidosDia = mysqli_query($conn, $consultaPedidosDia);

                                                                while ($rowPedidoDia = mysqli_fetch_array($resultPedidosDia)) {
                                                                    $idPedido = $rowPedidoDia['id'];

                                                                    // necesito los productos del pedido
                                                                    $consultaProductosPedido = "SELECT idProducto, cant FROM producto_pedido WHERE idPedido = '$idPedido'";
                                                                    $resultProductosPedido = mysqli_query($conn, $consultaProductosPedido);

                                                                    while ($rowProdPed = mysqli_fetch_array($resultProductosPedido)) {
                                                                        $idProducto = $rowProdPed['idProducto'];
                                                                        $cant = $rowProdPed['cant'];
                                                                        $totalProductosDia += $cant;

                                                                        // Precio del producto
                                                                        $consultaProducto = "SELECT precio FROM producto WHERE id = '$idProducto'";
                                                                        $resultProducto = mysqli_query($conn, $consultaProducto);
                                                                        $rowProducto = mysqli_fetch_array($resultProducto);
                                                                        $precio = $rowProducto['precio'];

                                                                        $totalCajaDia += $cant * $precio;
                                                                    }
                                                                }

                                                                // muestro los resultados de la tabla
                                                                echo "<tr>";
                                                                echo "<td>" . date('d/m/Y', strtotime($fecha)) . "</td>";
                                                                echo "<td>" . number_format($totalComensalesDia, 0, ',', '.') . "</td>";
                                                                echo "<td>" . number_format($totalProductosDia, 0, ',', '.') . "</td>";
                                                                echo "<td>" . number_format($totalCajaDia, 2, ',', '.') . " €</td>";
                                                                echo "</tr>";
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </section>
                </div>
            </div>
        </main>
    </div>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../bootstrap/js/mdb.umd.min.js"></script>
</body>

</html>