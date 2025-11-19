<?php
session_start();

require_once("../vendor/autoload.php");
include("../components/conexion.php");
include("seguridad.php");


$mpdf = new \Mpdf\Mpdf([]);
$idPedido = $_GET['idPedido'];

// datos del pedido
$consultaPedido = mysqli_query($conn, "SELECT * FROM pedido WHERE id = '$idPedido'");
$pedido = mysqli_fetch_array($consultaPedido);
$fechaPedido = $pedido["fecha"];
$dniCliente = $pedido["usuario"];
$numMesa = $pedido["numMesa"];

// los datos del cliente
$consultaCliente = mysqli_query($conn, "SELECT * FROM usuario WHERE dni = '$dniCliente'");
$cliente = mysqli_fetch_array($consultaCliente);
$nombreCliente = $cliente["nombre"];
$apellidoCliente = $cliente["apellido"];
$emailCliente = $cliente["email"];
$tlfCliente = $cliente["telefono"];

// los datos de los productos del pedido
$consultaLineas = mysqli_query($conn, "SELECT * FROM producto_pedido WHERE idPedido = '$idPedido'");

$total = 0;
$filasTabla = "";

while ($linea = mysqli_fetch_array($consultaLineas)) {
    $idProducto = $linea['idProducto'];
    $cantidad   = $linea['cant'];

    $consultaProducto = mysqli_query($conn, "SELECT * FROM producto WHERE id = '$idProducto'");
    $producto = mysqli_fetch_array($consultaProducto);

    $nombreProducto = $producto["nombre"];
    $precioUnitario = $producto["precio"];
    $subtotal = $precioUnitario * $cantidad;
    $total += $subtotal;

    $filasTabla .= "
        <tr>
            <td>
                $nombreProducto
            </td>
            <td class='text-center'>
                $cantidad
            </td>
            <td class='text-right'>" . number_format($subtotal, 2) . " €</td>
        </tr>
    ";
}


$html = "
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            font-size: 12px;
            color: #222222;
        }

        h1 {
            margin: 0;
            font-size: 22px;
        }

        .logo {
            width: 50px;
        }

        .subtitulo {
            font-size: 11px;
            color: #555555;
        }

        .separador {
            border: none;
            border-top: 1px solid #cccccc;
            margin: 8px 0 12px 0;
        }

        .tabla-cabecera {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }

        .tabla-cabecera td {
            vertical-align: top;
        }

        .tabla-datos {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .tabla-datos td {
            width: 50%;
            padding: 8px;
            border: 1px solid #dddddd;
            background-color: #f7f7f7;
        }

        .titulo-bloque {
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .tabla-datos p {
            margin: 2px 0;
        }

        /* Tabla de productos */
        .tabla-productos {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .tabla-productos th,
        .tabla-productos td {
            border: 1px solid #dddddd;
            padding: 6px;
        }

        .tabla-productos th {
            background-color: #3f5670;
            color: #ffffff;
            font-size: 12px;
        }

        .tabla-productos td {
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .fila-total {
            background-color: #f0f0f0;
        }

        .footer-factura {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
            color: #555555;
        }
    </style>
</head>
<body>

    <!-- CABECERA: LOGO + NOMBRE RESTAURANTE -->
    <table class='tabla-cabecera'>
        <tr>
            <td style='width:60px;'>
                <img src='../img/logo.png' class='logo'>
            </td>
            <td>
                <h1>El Quinto Pino</h1>
                <div class='subtitulo'>
                    Restaurante El Quinto Pino<br>
                    Ctra. De Mula, 37, Murcia - Alguazas · Tel: 968 620 913 · CIF: 12345678B
                </div>
            </td>
        </tr>
    </table>

    <hr class='separador'>

    <!-- DATOS CLIENTE + DATOS PEDIDO -->
    <table class='tabla-datos'>
        <tr>
            <!-- CLIENTE -->
            <td>
                <div class='titulo-bloque'>Datos del cliente</div>
                <p><strong>Nombre:</strong> $nombreCliente $apellidoCliente</p>
                <p><strong>DNI:</strong> $dniCliente</p>
                <p><strong>Email:</strong> $emailCliente</p>
                <p><strong>Teléfono:</strong> $tlfCliente</p>
            </td>

            <!-- PEDIDO -->
            <td>
                <div class='titulo-bloque'>Datos del pedido</div>
                <p><strong>Nº Pedido:</strong> $idPedido</p>
                <p><strong>Fecha:</strong> $fechaPedido</p>
                <p><strong>Mesa:</strong> $numMesa</p>
                <p><strong>Estado:</strong> Pagado</p>
            </td>
        </tr>
    </table>

    <!-- DETALLE DEL CONSUMO -->
    <div class='titulo-bloque' style='margin-bottom:4px;'>Detalle del consumo</div>

    <table class='tabla-productos'>
        <thead>
            <tr>
                <th>Producto</th>
                <th class='text-center'>Cantidad</th>
                <th class='text-right'>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            $filasTabla
            <tr class='fila-total'>
                <td colspan='2' class='text-right'><strong>Total</strong></td>
                <td class='text-right'><strong>" . number_format($total, 2, ',', '.') . " €</strong></td>
            </tr>
        </tbody>
    </table>

    <div class='footer-factura'>
        Gracias por su visita a El Quinto Pino.<br>
        Esperamos volver a verle pronto.<br><br>
        Factura generada automáticamente como parte de la práctica de 2º DAW.
    </div>

</body>
</html>
";

$mpdf->writeHtml($html);
$mpdf->Output();
