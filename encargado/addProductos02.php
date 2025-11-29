<?php
session_start();

include("../components/conexion.php");

include("seguridad.php");

// funcionalidad para añadir el producto a la base de datos
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    require_once __DIR__ . "/../components/demo.php";
    if (DEMO_MODE && demo_productos_creados($conn) >= DEMO_MAX_PRODUCTOS_NUEVOS) {
        demo_block('limite_productos');
        header("LOCATION: /encargado/addProductos.php");
        exit;
    }

    $nombre = $_POST["nombre"];
    $precio = $_POST["precio"];
    $stock = $_POST["stock"];
    $bloqueado = $_POST["bloqueado"];

    if ($bloqueado == "si") {
        $bloqueado = 0;
    } else {
        $bloqueado = 1;
    }

    $cat = $_POST["cat"];

    // cogemos los datos de la categoría
    $consulta2 = "SELECT * FROM categoria WHERE nombre = '$cat'";
    $result = mysqli_query($conn, $consulta2);
    $row = mysqli_fetch_array($result);

    $cat = $row["id"];
    $estadoCat = $row["estado"];

    // añado el producto a la base de datos
    $consulta = "INSERT INTO producto (nombre, precio, stock, estado, categoria, estado_categoria, imagen)
                             VALUES('$nombre', '$precio', '$stock', '$bloqueado', '$cat', '$estadoCat', '')";


    $result = mysqli_query($conn, $consulta);


    // selecciono el último ID del insert en la bd
    $cod = mysqli_insert_id($conn);

    if ($cod < 10) {
        $ima = "../img_productos/00" . $cod . ".png";
    } else if ($cod < 100) {
        $ima = "../img_productos/0" . $cod . ".png";
    } else {
        $ima = "../img_productos/" . $cod . ".png";
    }


    // actualizo el producto con la imagen, teniendo de nombre el ID
    mysqli_query($conn, "UPDATE producto SET imagen = '$ima' WHERE id = '$cod'");

    // se guarda la imagen en la bbdd
    $ruta = $ima;
    COPY($_FILES["imagen"]["tmp_name"], $ruta);

    mysqli_close($conn);

    header("LOCATION: /encargado/encargado.php");
    exit;
}
