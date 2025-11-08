<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("LOCATION: ../index.php");
    exit;
}

include("../components/conexion.php");
$dni = $_SESSION["dni"];

// si el cliente ya ha puesto los comensales, vamos a la carta
$result = mysqli_query($conn, "SELECT comensales FROM reserva WHERE dni = '$dni'");
$row = mysqli_fetch_assoc($result);
if ($row["comensales"] != 0) {
    header("LOCATION: carta.php");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $comensales = $_POST["comensales"];

    mysqli_query($conn, "UPDATE reserva SET comensales = '$comensales' WHERE dni = '$dni'");

    header("LOCATION: carta.php");
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
        // con los comensales, no podemos hacer otra cosa; así que eliminamos el navbar
        // include("navbar.php");
        $usuario = $_SESSION["usuario"];
        ?>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="container my-4 flex-grow-1">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4">
                        <header class="text-center mb-3">
                            <h4 class="display-6">Indica los comensales</h4>
                        </header>
                        <div class="bg-body rounded-4 p-3 p-sm-4">

                            <form action="" method="post">


                                <div class="row">
                                    <div class="col-auto">
                                        <label class="col-form-label" for="comensales">Comensales</label>
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" id="comensales" name="comensales" min="1"
                                            max="15" placeholder="Introduce un número..." />
                                    </div>
                                    <div class="col-auto">
                                        <button class="btn btn-primary" type="submit">Realizar reserva</button>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </main>
    </div>


    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>