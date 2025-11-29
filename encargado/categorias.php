<?php
session_start();

include("../components/conexion.php");

include("seguridad.php");

$active = "productos";

require_once __DIR__ . "/../components/demo.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombreCat = $_POST["categ"];

    if (DEMO_MODE && demo_categorias_creadas($conn) >= DEMO_MAX_CATEGORIAS_NUEVAS) {
        demo_block('limite_categorias');
    } else {
        $consulta = "INSERT INTO categoria (nombre, estado) VALUES('$nombreCat', 1)";
        $result = mysqli_query($conn, $consulta);
    }
}

?>

<!DOCTYPE html>
<html lang="es" data-bs-theme="dark">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>El Quinto Pino - Categorías</title>
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
                <!-- 1 -->
                <div class="col-12 col-lg-6 col-xl-5">
                    <section class="bg-dark-subtle border rounded-4 p-4 p-sm-4">
                        <header class="text-center mb-3">
                            <h4 class="display-6 mb-4">Categorías</h4>
                        </header>

                        <section class="row justify-content-center">
                            <div class="col-10 tbl-wrap">
                                <table class="tbl">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th colspan="2">Editar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // realizo una tabla con todos los productos de la base de datos.
                                        $consulta = "SELECT * FROM categoria";

                                        $result = mysqli_query($conn, $consulta);

                                        while ($row = mysqli_fetch_array($result)) {
                                            $id = $row['id'];
                                            $estado = $row['estado'];
                                            if ($estado == 1) {
                                                print("<tr>");
                                            } else {
                                                print("<tr class='tbl-row-alert'>");
                                            }
                                            print("<td>");
                                            print($row['nombre']);
                                            print("</td>");
                                            print("</td>");
                                            print("<td>");
                                            print("<a href='editarCategoria.php?id=$id' class='btn btn-primary'>Editar</a>");
                                            print("</td>");
                                            print("<td>");
                                            if ($estado == 1) {
                                                print("<a href='bloquearCategoria.php?id=$id' class='btn btn-danger'>Bloquear</a>");
                                            } else {
                                                print("<a href='desbloquearCategoria.php?id=$id' class='btn btn-success'>Desbloquear</a>");
                                            }
                                            print("</td>");
                                            print("</tr>");
                                        }

                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                </div>

                <!-- 2 -->
                <div class="col-12 order-first col-lg-6 order-lg-0 col-xl-5">
                    <section class="bg-dark-subtle border rounded-4 p-4 p-sm-4">
                        <header class="text-center mb-3">
                            <h4 class="display-6 mb-4">Añade una categoría</h4>
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
                                            placeholder="Categoría" required />
                                        <label for="categ">Nombre</label>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <input
                                            type="submit"
                                            name="addCat"
                                            id="addCat"
                                            class="btn btn-success" value="Añadir categoría">
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