<?php
include 'funciones.php';
$id = $_GET['id'] ?? null;

if($id !== null){
    eliminarVisita($id);
}
header("Location: index.php");
?>