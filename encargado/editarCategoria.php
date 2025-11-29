<?php
session_start();

include("../components/conexion.php");

include("seguridad.php");

$active = "productos";

$id = $_GET["id"];

$consulta = "SELECT * FROM categoria WHERE id = '$id'";

$result = mysqli_query($conn, $consulta);

$row = mysqli_fetch_array($result);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = $_POST["categ"];

    $consulta2 = "UPDATE categoria
                SET nombre = '$nombre'
                WHERE id = '$id'";

    $result2 = mysqli_query($conn, $consulta2);

    header("LOCATION: " . BASE_PATH . "/encargado/categorias.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>El Quinto Pino - Editar Categoría</title>
    <link rel="shortcut icon" href="../img/ico.png" type="image/x-icon">
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css" />
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" /> -->
    <link rel="stylesheet" href="../styles.css" />
    <link rel="stylesheet" href="../tailwind/tailwind.css" />
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
            <div class="row justify-content-center gap-5 gap-lg-0">
                <div class="col-12 col-md-10 order-first col-lg-8 col-xl-6">
                    <section class="bg-dark-subtle border rounded-4 p-4 p-sm-4">
                        <header class="text-center mb-3">
                            <h4 class="display-6 mb-4">Edita la categoría <?php echo $row["nombre"] ?></h4>
                        </header>
                        <section class="row justify-content-center ">
                            <div class="col-10 table-responsive">
                                <form action="" method="post">
                                    <div class="form-floating mb-3">
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="categ"
                                            id="categ"
                                            placeholder="Categoría"
                                            value="<?php echo $row["nombre"] ?>" required />
                                        <label for="categ">Nombre</label>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <input
                                            type="submit"
                                            name="addCat"
                                            id="addCat"
                                            class="btn btn-success" value="Editar categoría">
                                        </input>
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