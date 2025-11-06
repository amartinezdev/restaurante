<?php
session_start();
include("components/conexion.php");

?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>El Quinto Pino</title>
  <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css" />
  <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" /> -->
  <link rel="stylesheet" href="styles.css" />
</head>

<body>
  <div class="container-fluid vh-100">

    <?php
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $dni = $_POST["dni"];
      $pw = $_POST["pw"];

      $consulta = "SELECT * FROM usuario WHERE dni = '$dni' AND password = '$pw'";

      $result = mysqli_query($conn, $consulta);
      $row = mysqli_num_rows($result);
      $row2 = mysqli_fetch_array($result);

      if ($row == 1) {

        $_SESSION["usuario"] = $row2["nombre"];
        $_SESSION["dni"] = $dni;
        $_SESSION["rol"] = $row2["rol"];


        if ($row2["rol"] == 0) {
          header("LOCATION: cliente/cliente.php");
          $mensaje = "Soy rol 0";
        } else if ($row2["rol"] == 1) {
          header("LOCATION: camarero/camarero.php");
          $mensaje = "Soy rol 1";
        } else if ($row2["rol"] == 2) {
          header("LOCATION: encargado/encargado.php");
          $mensaje = $row2['rol'];
        }
      } else if ($row == 0) {
        $mensaje = "Usuario o contraseña incorrectos.";
      }
    }

    ?>

    <!-- HEADER -->
    <?php
    include("components/header.php");

    ?>

    <!-- SECTION -->
    <section class="row justify-content-center align-items-center h-75">
      <!-- MAIN -->
      <main class="login col-12 col-sm-8 col-md-7 col-lg-5 col-xl-4 col-xxl-3 bg-success p-4 rounded">
        <h3 class="text-center text-white mb-4">Accede al portal</h3>

        <form action="" method="post" enctype="multipart/form-data">
          <!-- Usuario -->
          <div class="form-floating mb-3">
            <input type="text" class="form-control" id="dni" name="dni" placeholder="Usuario" required />
            <label for="dni">DNI</label>
          </div>

          <!-- Contraseña -->
          <div class="form-floating mb-3">
            <input type="password" class="form-control" id="pw" name="pw" placeholder="Contraseña" required />
            <label for="pw">Contraseña</label>
          </div>
          <div class="d-grid ">
            <button type="submit" class="btn btn-light fw-bold lead d-flex align-items-center justify-content-center">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-box-arrow-in-right" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-2a.5.5 0 0 0-1 0v2A1.5 1.5 0 0 0 6.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2h-8A1.5 1.5 0 0 0 5 3.5v2a.5.5 0 0 0 1 0z" />
                <path fill-rule="evenodd" d="M11.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5H1.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z" />
              </svg>
              &nbsp;Entrar</button>
          </div>

          <div class="text-center mt-4 text-warning">
            <?php
            if (isset($mensaje)) {
              echo $mensaje;
            }

            ?>
          </div>

          <hr class="mt-4" />

          <div class="row justify-content-center text-center">
            <div class="col-auto d-flex align-items-center">
              <p class="mb-0 me-2">¿No tienes cuenta?</p>
              <a href="registro.php" class="btn btn-outline-light fw-bold">Regístrate</a>
            </div>
          </div>


        </form>
      </main>
    </section>

    <video class="video-fondo" autoplay muted loop>
      <source src="img/fondo.mov" type="video/mp4" />
      Tu navegador no soporta videos HTML5.
    </video>

    <!-- FOOTER -->
    <footer class="row">
      <div class="col-12"></div>
    </footer>
  </div>

  <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>