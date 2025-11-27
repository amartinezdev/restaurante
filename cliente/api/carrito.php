<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

function jsonError($mensaje, $code = 400)
{
    http_response_code($code);
    echo json_encode(["ok" => false, "error" => $mensaje]);
    exit;
}

if (!isset($_SESSION["usuario"]) || $_SESSION["rol"] != 0) {
    jsonError("No autorizado.", 401);
}

// conexion.php imprime mysqli_error($conn) directamente; lo capturamos para
// no romper la respuesta JSON si algún día devuelve algo.
ob_start();
include("../../components/conexion.php");
ob_end_clean();

$dni = $_SESSION["dni"];

$resultReserva = mysqli_query($conn, "SELECT numMesa, comensales FROM reserva WHERE dni = '$dni'");
$reserva = $resultReserva ? mysqli_fetch_assoc($resultReserva) : null;

if (!$reserva) {
    jsonError("No tienes una reserva activa.");
}

if (!isset($_SESSION["carrito"])) {
    $_SESSION["carrito"] = [];
}

$action = $_POST["action"] ?? "";

// Misma lógica que las acciones GET/POST de carta.php, solo que aquí no
// recargamos la página: mutamos la sesión y devolvemos el carrito resultante.
switch ($action) {
    case "add":
        $id = (int)($_POST["id"] ?? 0);
        if ($id <= 0) {
            jsonError("Producto no válido.");
        }

        $_SESSION["carrito"][$id] = ($_SESSION["carrito"][$id] ?? 0) + 1;
        break;

    case "remove":
        $id = (int)($_POST["id"] ?? 0);
        unset($_SESSION["carrito"][$id]);
        break;

    case "updateCantidad":
        $cantidades = $_POST["cantidades"] ?? [];
        if (!is_array($cantidades)) {
            jsonError("Datos de cantidad no válidos.");
        }

        foreach ($cantidades as $id => $cantidad) {
            $id = (int)$id;
            if (isset($_SESSION["carrito"][$id])) {
                $_SESSION["carrito"][$id] = max(1, (int)$cantidad);
            }
        }
        break;

    case "clear":
        $_SESSION["carrito"] = [];
        break;

    default:
        jsonError("Acción no reconocida.");
}

// Construimos el estado actual del carrito para devolverlo al cliente.
// Si un producto ya no existe (borrado tras añadirlo), lo quitamos del carrito.
$items = [];
$total = 0.0;

foreach ($_SESSION["carrito"] as $idProd => $cant) {
    $consulta = mysqli_query($conn, "SELECT nombre, precio, stock FROM producto WHERE id = $idProd");
    $producto = $consulta ? mysqli_fetch_assoc($consulta) : null;

    if (!$producto) {
        unset($_SESSION["carrito"][$idProd]);
        continue;
    }

    $precio = (float)$producto["precio"];
    $cant = (int)$cant;
    $subtotal = $precio * $cant;
    $total += $subtotal;

    $items[] = [
        "id" => (int)$idProd,
        "nombre" => $producto["nombre"],
        "precioFmt" => number_format($precio, 2) . " €",
        "cantidad" => $cant,
        "stockMax" => (int)$producto["stock"],
        "subtotalFmt" => number_format($subtotal, 2) . " €",
    ];
}

echo json_encode([
    "ok" => true,
    "carrito" => $items,
    "totalFmt" => number_format($total, 2) . " €",
]);
