<?php
session_start();

function obtenerVisitas() {
    return $_SESSION['visitas'] ?? [];
}

function guardarVisita($datos, $id = null) {
    if ($id === null) {
        
        $_SESSION['visitas'][] = $datos;
    } else {
        
        $_SESSION['visitas'][$id] = $datos;
    }
}

function eliminarVisita($id) {
    unset($_SESSION['visitas'][$id]);
}
?>