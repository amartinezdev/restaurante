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

        $consultaCobrar = "SELECT * FROM pedido WHERE estado = 1";
        $resultadoCobrar = mysqli_query($conn, $consultaCobrar);
        $numPedidosCobrar = mysqli_num_rows($resultadoCobrar);
        ?>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="container my-4 flex-grow-1">
            <div class="row justify-content-center">
                <div class="col-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4 text-center">
                        <header class="text-center mb-3">
                            <h4 class="h4 mb-1">Bienvenido, <?php echo $usuario ?></h4>
                        </header>
                        <?php
                        if ($numPedidos == 0) {
                        ?>
                            <p class="display-6">No hay pedidos sin servir. <br>¡Buen trabajo!</p>
                        <?php
                        } else if ($numPedidos == 1) {
                        ?>
                            <p class="display-6">Hay <?php echo $numPedidos ?> pedido sin servir</p>
                        <?php
                        } else {
                        ?>
                            <p class="display-6">Hay <?php echo $numPedidos ?> pedidos sin servir</p>
                        <?php
                        }
                        ?>
                        <?php
                        if ($numPedidos >= 1) {
                            echo "<a href='pedidos.php' class='btn btn-primary mt-5'>Ir a pedidos</a>";
                        }

                        ?>

                    </section>
                </div>

                <div class="col-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4 text-center">
                        <header class="text-center mb-3">
                            <h4 class="h4 mb-1">Pendientes de cobro</h4>
                        </header>
                        <?php
                        if ($numPedidosCobrar == 0) {
                        ?>
                            <p class="display-6">No hay cobros pendientes.</p>
                        <?php
                        } else if ($numPedidosCobrar >= 1) {
                        ?>
                            <p class="display-6">Hay pendiente de cobro: <?php echo $numPedidosCobrar ?></p>
                        <?php
                        }
                        ?>
                        <?php
                        if ($numPedidosCobrar >= 1) {
                            echo "<a href='cobros.php' class='btn btn-primary mt-5'>Ir a Cobros</a>";
                        }

                        ?>

                    </section>
                </div>
            </div>
        </main>
    </div>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script>
        // recargamos la página por si hay pedidos, que aparezcan en el home
        function refrescarPagina() {
            location.reload();

        }

        setTimeout(refrescarPagina, 5000);
    </script>
</body>

</html>