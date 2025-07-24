<?php
$host = "localhost";
$usuario = "root";
$clave = "12345678";
$database = "coneccion";

$conexion = mysqli_connect($host, $usuario, $clave, $database);

if (!$conexion) {
    die("Error de conexión: " . mysqli_connect_error());
}
?>