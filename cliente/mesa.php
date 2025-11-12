<?php
session_start();

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != 0) {
    header("LOCATION: ../index.php");
    exit;
}

include("../components/conexion.php");

$dni = $_SESSION["dni"];

$consulta = "SELECT * FROM reserva WHERE dni = '$dni'";
$result = mysqli_query($conn, $consulta);
$row = mysqli_num_rows($result);

if ($row > 0) {
    $_SESSION["haElegidoMesa"] = true;
    header("LOCATION: eligeComensales.php");
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
        $usuario = $_SESSION["usuario"];


        ?>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="container my-4 flex-grow-1">
            <div class="row justify-content-center">
                <div class="col-12 col-md-12">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4">
                        <header class="text-center mb-3">
                            <h4 class="h4 mb-1">Bienvenido, <?php echo $usuario; ?></h4>
                            <p class="text-body-secondary mb-0">Elige tu mesa.</p>
                        </header>

                        <div class="bg-body rounded-4 p-3 p-sm-4">

                            <?php
                            $consulta = "SELECT * FROM mesa";
                            $result = mysqli_query($conn, $consulta);

                            $numMesas = mysqli_num_rows($result);



                            echo '<div class="row g-3 g-sm-4 justify-content-around">';
                            // pinto todas las mesas que hayan, por si se borran o se añaden.
                            while ($row = mysqli_fetch_array($result)) {
                                $mesa = $row['numMesa'];
                                $disponible = $row['estado'];

                                // si esta disponible, la pintamos de verde, sino, de rojo y desactivo el botón
                                if ($disponible == 1) {
                                    echo '<div class="col-12 col-md-6 col-lg-4 d-flex">';
                                    echo ' <article class="card mesa card-mesa shadow-sm w-100 border-success">';
                                    echo ' <div class="card-body text-center">';
                                    echo ' <img src="../img/mesa2.png" class="img-fluid mx-auto d-block mb-2" alt="Mesa ' . $mesa . '" loading="lazy">';
                                    echo ' <h2 class="h6 mb-1">Mesa ' . $mesa . '</h2>';
                                    echo ' <p class="mb-3 text-success">Disponible</p>';
                                    echo ' <a href="reserva.php?mesa=' . $mesa . '" class="btn btn-light btn-sm w-100" aria-label="Seleccionar mesa ' . $mesa . '">Elegir</a>';
                                    echo ' </div>';
                                    echo ' </article>';
                                    echo '</div>';
                                } else {
                                    echo '<div class="col-12 col-md-6 col-lg-4 d-flex">';
                                    echo ' <article class="card mesa card-mesa shadow-sm w-100 border-danger">';
                                    echo ' <div class="card-body text-center">';
                                    echo ' <img src="../img/mesa2.png" class="img-fluid mx-auto d-block mb-2" alt="Mesa ' . $mesa . '" loading="lazy">';
                                    echo ' <h2 class="h6 mb-1">Mesa ' . $mesa . '</h2>';
                                    echo ' <p class="mb-3 text-danger fw-semibold">No disponible</p>';
                                    echo ' <button class="btn btn-secondary btn-sm w-100" disabled aria-disabled="true">Ocupada</button>';
                                    echo ' </div>';
                                    echo ' </article>';
                                    echo '</div>';
                                }
                            }

                            echo '</div>';
                            ?>

                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>