<?php
include 'funciones.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $datos = [
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'cedula' => $_POST['cedula'],
        'motivo' => $_POST['motivo'],
        'fecha' => date('d/m/Y')
    ];

    $id = $_POST['id'] ?? null;
    guardarVisita($datos, $id);
}

header("Location: index.php");
?>