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
    <title>El Quinto Pino - Agregar Productos</title>
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
            <div class="row justify-content-center">
                <div class="col-12 col-md-10 col-lg-8 col-xl-6">
                    <section class="bg-dark-subtle border rounded-4 p-3 p-sm-4">
                        <header class="text-center mb-3  justify-content-center">
                            <h2 class="display-6">Añadir un producto
                                <a href="/encargado/encargado.php" class="float-end link-light"> <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-arrow-left-circle" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-4.5-.5a.5.5 0 0 1 0 1H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5z" />
                                    </svg></a>
                            </h2>
                        </header>
                        <div class="row justify-content-center">
                            <form action="addProductos02.php" method="post" class="col-12" enctype="multipart/form-data">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" required />
                                    <label for="nombre">Nombre</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="precio" name="precio" placeholder="Precio" required />
                                    <label for="precio">Precio</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="stock" name="stock" placeholder="Stock" required />
                                    <label for="stock">Stock</label>
                                </div>

                                <div class="row align-items-center mb-3 g-2">
                                    <div class="col-auto">
                                        <label for="bloqueado" class="form-label">Bloqueado</label>
                                    </div>
                                    <div class="col-3">
                                        <select
                                            class="form-select form-select-lg"
                                            name="bloqueado"
                                            id="bloqueado">

                                            <option value="no" selected>No</option>
                                            <option value="si">Si</option>

                                        </select>
                                    </div>
                                    <div class="w-100 d-block d-sm-none"></div>
                                    <div class="col-auto">
                                        <label for="cat" class="form-label">Categoría</label>
                                    </div>
                                    <div class="col-auto col-sm">
                                        <select
                                            class="form-select form-select-lg"
                                            name="cat"
                                            id="cat">
                                            <?php
                                            // select de categorías para añadir dinamismo
                                            $consultaCat = "SELECT nombre FROM categoria";
                                            $resultCat = mysqli_query($conn, $consultaCat);

                                            while ($rowCat = mysqli_fetch_array($resultCat)) {
                                                print("<option>" . $rowCat["nombre"] . "</option>");
                                            }

                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <input
                                        type="file"
                                        class="form-control"
                                        name="imagen"
                                        id="imagen"
                                        placeholder="Añade una imagen"
                                        aria-describedby="imagen" required />
                                </div>


                                <div class="d-grid ">
                                    <button type="submit" class="btn btn-success fw-bold d-flex align-items-center justify-content-center gap-2 py-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z" />
                                            <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
                                        </svg>
                                        &nbsp; Añadir</button>
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