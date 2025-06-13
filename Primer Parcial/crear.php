<?php include 'funciones.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nueva Visita</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white">
                <h2 class="text-center">Nueva Visita</h2>
            </div>
            <div class="card-body">
                <form action="guardar.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nombre:</label>
                        <input type="text" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellido:</label>
                        <input type="text" name="apellido" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Cédula:</label>
                        <input type="text" name="cedula" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Motivo:</label>
                        <select name="motivo" class="form-select" required>
                            <option value="Rutina">Rutina</option>
                            <option value="Emergencia">Emergencia</option>
                            <option value="Cirugia">Cirugia</option>
                        </select>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>
                </form>
                <a href="index.php" class="btn btn-secondary mt-3">Cancelar</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>