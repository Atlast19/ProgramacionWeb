<?php
session_start();
$mensajeError = $_SESSION['error'] ?? '';
session_destroy();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-4">
      <div class="card shadow">
        <div class="card-header text-center">
          <h4>Iniciar Sesión</h4>
        </div>
        <div class="card-body">
          <?php if ($mensajeError): ?>
            <div class="alert alert-danger"><?php echo $mensajeError; ?></div>
          <?php endif; ?>

          <form action="login.php" method="POST">
            <div class="mb-3">
              <label for="usuario" class="form-label">Usuario</label>
              <input type="text" class="form-control" name="username" required>
            </div>
            <div class="mb-3">
              <label for="clave" class="form-label">Contraseña</label>
              <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>