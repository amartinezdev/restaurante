<?php
session_start();
include("../components/conexion.php");
include("seguridad.php");

$dni = $_SESSION["dni"];

$result = mysqli_query($conn, "SELECT * FROM reserva WHERE dni = '$dni'");
$row = mysqli_fetch_assoc($result);

if (isset($_SESSION['idPedido'])) {
    $idPedidoSesion = $_SESSION['idPedido'];
    $checkPedido = mysqli_query($conn, "SELECT * FROM pedido WHERE id = '$idPedidoSesion'");
    
    if (mysqli_num_rows($checkPedido) > 0) {
        $rowPedidoCheck = mysqli_fetch_array($checkPedido);
        // Si el pedido ya está pagado, limpiamos las variables de sesión
        if ($rowPedidoCheck['estado'] == 2) {
            unset($_SESSION['idPedido']);
            unset($_SESSION['mesa']);
            unset($_SESSION['comensales']);
            unset($_SESSION["haElegidoComensales"]);
            unset($_SESSION["haElegidoMesa"]);
            // También limpiamos el carrito por seguridad
            unset($_SESSION['carrito']);
        }
    }
}

if ($row["comensales"] != 0) {
    $_SESSION["haElegidoComensales"] = true;
    header("LOCATION: carta.php");
    exit;
}

if (!isset($_SESSION["haElegidoMesa"])) {
    header("LOCATION: mesa.php");
    exit;
}

// realizamos update de los comensales
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $comensales = $_POST["comensales"];

    mysqli_query($conn, "UPDATE reserva SET comensales = '$comensales' WHERE dni = '$dni' AND comensales = 0");

    $_SESSION["haElegidoComensales"] = true;
    header("LOCATION: carta.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>El Quinto Pino - Comensales</title>
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
        ?>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="container my-4 flex-grow-1">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4">
                        <header class="text-center mb-3">
                            <h4 class="display-6">Indica los comensales</h4>
                            <p class="small text-muted">Mesa <?php echo $row["numMesa"] ?></p>
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
                                        <p class="small text-muted"><i>Máximo 15</i></p>
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