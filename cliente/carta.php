<?php
session_start();

include("../components/conexion.php");
include("seguridad.php");

// VARIABLES DE SESIÓN
$usuario = $_SESSION["usuario"];
$dni = $_SESSION["dni"];


$result = mysqli_query($conn, "SELECT * FROM reserva WHERE dni = '$dni'");
$row = mysqli_fetch_assoc($result);

$mesa = $row["numMesa"];
$comensales = $row["comensales"];

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

        $consulta = "SELECT * FROM usuario WHERE dni = $dni";
        $result = mysqli_query($conn, $consulta);

        $row = mysqli_fetch_array($result);

        mysqli_close($conn);

        ?>
        <main class="container my-4 flex-grow-1">


            <!-- CARTA -->
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4">
                        <header class="justify-content-between align-items-center">
                            <h2 class="display-6 m-0 text-center flex-grow-1">Carta</h2>
                        </header>


                    </section>
                </div>



                <!-- PEDIDO -->
                <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4">
                        <header class="justify-content-between align-items-center">
                            <h2 class="display-6 m-0 text-center flex-grow-1">Pedido</h2>
                            <p class="text-center text-muted small"><i>Mesa <?php echo $mesa ?> - <?php echo $comensales ?> comensales</i></p>
                        </header>


                    </section>
                </div>
            </div>
        </main>
    </div>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>