<?php
session_start();
include('Conexion.php');

$usuario = $_POST['username'] ?? '';
$clave = $_POST['password'] ?? '';

// Consulta segura con prepared statement
$sql = "SELECT * FROM usuarios WHERE username = ? AND password = ?";
$stmt = mysqli_prepare($conexion, $sql);
mysqli_stmt_bind_param($stmt, "ss", $usuario, $clave);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($resultado) == 1) {
    // Login exitoso
    header("Location: GenerarFactura.php");
} else {
    // Error en login
    $_SESSION['error'] = "Usuario o contraseña incorrectos.";
    header("Location: index.php");
}
?>