<?php include 'funciones.php'?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Consulta Media</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
</head>
<body class="bg-light">
    <div class="contenedor py-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h1 class="text-center">Restro de Visiatas</h1>
            </div>
            <div clas="card-body">
                <a href="crear.php" class="btn btn-success mb-4">Nueva Vista</a>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead class="table-dark">
                            <th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Cedula</th>
                                <th>Motivo</th>
                                <th>Fecha</th>
                                <th>Aciones</th>
                            </th>
                        </thead>
                        <tbody>
                            <?php
                        foreach(obtenerVisitas() as $id => $visitas): ?>
                        <tr>
                            <td><?= htmlspecialchars($visitas['nombre'])?></td>
                            <td><?= htmlspecialchars($visitas['apellido'])?></td>
                            <td><?= htmlspecialchars($visitas['cedula'])?></td>
                            <td><?= htmlspecialchars($visitas['motivo'])?></td>
                            <td><?= htmlspecialchars($visitas['fecha'])?></td>
                            <td>
                                <a href="editar.php?id=<?= $id?>" class="btn btn-ms btn-warning">Editar</a>
                                <a href="eliminar.php?id=<?= $id?>" class="btn btn-ms btn-danger">Eliminar</a>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                </div>
            </div>

        </div>
    </div>

    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
</body>
</html>