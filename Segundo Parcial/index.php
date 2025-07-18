<?php

session_start();

// Inicializar array de visitas
if (!isset($_SESSION['visitas'])) {
    $_SESSION['visitas'] = [];
}

// Procesar acciones: agregar, eliminar o actualizar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Acción de eliminar
    if (isset($_POST['eliminar'])) {
        $id = $_POST['id'];
        $_SESSION['visitas'] = array_filter($_SESSION['visitas'], function($visita) use ($id) {
            return $visita['id'] != $id;
        });
        $mensaje = "Visita eliminada correctamente";
    }
    // Acción de actualizar
    elseif (isset($_POST['actualizar'])) {
        $id = $_POST['id'];
        foreach ($_SESSION['visitas'] as &$visita) {
            if ($visita['id'] == $id) {
                $visita['nombre'] = $_POST['nombre'];
                $visita['apellido'] = $_POST['apellido'];
                $visita['telefono'] = $_POST['telefono'];
                $visita['email'] = $_POST['email'];
                $visita['fecha'] = date('Y-m-d H:i:s');
                break;
            }
        }
        $mensaje = "Visita actualizada correctamente";
    }
    // Acción de agregar
    else {
        $telefono = $_POST['telefono'] ?? '';
        $nombre = $_POST['nombre'] ?? '';
        $apellido = $_POST['apellido'] ?? '';
        $email = $_POST['email'] ?? '';
        
        if (!empty($telefono) && !empty($nombre) && !empty($apellido)) {
            $nuevaVisita = [
                'id' => uniqid(),
                'telefono' => $telefono,
                'nombre' => $nombre,
                'apellido' => $apellido,
                'email' => $email,
                'fecha' => date('Y-m-d H:i:s')
            ];
            
            array_unshift($_SESSION['visitas'], $nuevaVisita);
            $mensaje = "Visita registrada exitosamente!";
        } else {
            $error = "Todos los campos son necesarios";
        }
    }
}

// Obtener visita para editar
$editarVisita = null;
if (isset($_GET['editar'])) {
    foreach ($_SESSION['visitas'] as $visita) {
        if ($visita['id'] == $_GET['editar']) {
            $editarVisita = $visita;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Visitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; padding-top: 20px; }
        .card { margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .table-responsive { overflow-x: auto; }
        .btn-sm { padding: 0.25rem 0.5rem; font-size: 0.875rem; }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Registro de Visitas</h1>
        
        <!-- Formulario de Registro/Edición -->
        <div class="card">
            <div class="card-header <?= $editarVisita ? 'bg-warning' : 'bg-primary' ?> text-white">
                <h2 class="h5 mb-0"><?= $editarVisita ? 'Editar Visita' : 'Nueva Visita' ?></h2>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php elseif (isset($mensaje)): ?>
                    <div class="alert alert-success"><?= $mensaje ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <?php if ($editarVisita): ?>
                        <input type="hidden" name="id" value="<?= $editarVisita['id'] ?>">
                    <?php endif; ?>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre*</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="<?= $editarVisita ? htmlspecialchars($editarVisita['nombre']) : '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido*</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" 
                                   value="<?= $editarVisita ? htmlspecialchars($editarVisita['apellido']) : '' ?>" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono*</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" 
                                   value="<?= $editarVisita ? htmlspecialchars($editarVisita['telefono']) : '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= $editarVisita ? htmlspecialchars($editarVisita['email']) : '' ?>">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <?php if ($editarVisita): ?>
                            <a href="index.php" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" name="actualizar" class="btn btn-warning">Actualizar Visita</button>
                        <?php else: ?>
                            <div></div> <!-- Esto es un Espaciador -->
                            <button type="submit" class="btn btn-primary">Registrar Visita</button>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-success text-white">
                <h2 class="h5 mb-0">Visitas Registradas</h2>
            </div>
            <div class="card-body">
                <?php if (empty($_SESSION['visitas'])): ?>
                    <p class="text-muted">No hay visitas registradas aún.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Teléfono</th>
                                    <th>Email</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($_SESSION['visitas'] as $visita): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($visita['nombre']) ?></td>
                                        <td><?= htmlspecialchars($visita['apellido']) ?></td>
                                        <td><?= htmlspecialchars($visita['telefono']) ?></td>
                                        <td><?= htmlspecialchars($visita['email']) ?></td>
                                        
                                        <td class="d-flex gap-2">
                                            <a href="index.php?editar=<?= $visita['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                            <form method="POST" action="">
                                                <input type="hidden" name="id" value="<?= $visita['id'] ?>">
                                                <button type="submit" name="eliminar" class="btn btn-sm btn-danger" 
                                                        onclick="return confirm('Estas seguro de eliminar esta visita?')">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>