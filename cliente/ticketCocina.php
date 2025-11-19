<?php
session_start();

require_once '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;

include("../components/conexion.php");
include("seguridad.php");
