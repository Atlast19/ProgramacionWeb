<?php
include 'funciones.php';
$id = $_GET['id'] ?? null;
$visitas = obtenerVisitas();
$visita = $visitas[$id] ?? null;

if (!$visita) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Editar Visita </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
        
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7">
                <div class="card border-0 shadow">
                    <div class="card-header bg-primary text-white py-3">
                        <h2 class="text-center mb-0">Editar Visita</h2>
                    </div>
                    <div class="card-body p-2">
                        <form action="guardar.php" method="POST">
                            <input type="hidden" name="id" value="<?= $id ?>">

                            <div class="mb-2">
                                <label class="form-label fw-bold">Nombre:</label>
                                <input type="text" name="nombre" class="form-control form-control-lg" 
                                       value="<?= htmlspecialchars($visita['nombre']) ?>" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-bold">Apellido:</label>
                                <input type="text" name="apellido" class="form-control form-control-lg" 
                                       value="<?= htmlspecialchars($visita['apellido']) ?>" required>
                            </div>

                            <div class="mb-2">
                                <label class="form-label fw-bold">Cédula:</label>
                                <input type="text" name="cedula" class="form-control form-control-lg" 
                                       value="<?= htmlspecialchars($visita['cedula']) ?>" required>
                            </div>
                            
                            <div class="mb-2">
                                <label class="form-label fw-bold">Motivo:</label>
                                <select name="motivo" class="form-select form-select-lg" required>
                                    <option value="Rutina" <?= ($visita['motivo'] == 'Rutina') ? 'selected' : '' ?>>Rutina</option>
                                    <option value="Emergencia" <?= ($visita['motivo'] == 'Emergencia') ? 'selected' : '' ?>>Emergencia</option>
                                    <option value="Cirugia" <?= ($visita['motivo'] == 'Cirugia') ? 'selected' : '' ?>>Cirugia</option>
                                </select>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="index.php" class="btn btn-secondary btn-lg px-4">Cancelar</a>
                                <button type="submit" class="btn btn-primary btn-lg px-4">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>