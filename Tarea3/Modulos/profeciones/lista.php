<?php
include("../../libreria/principal.php");

define("PAGINA_ACTUAL","profeciones");
Plantilla::aplicar();

$profeciones = DBx::list("profeciones");

?>
<h4>Listado de Profeciones</h4>

<div class="text-end mb-3">
    <a href="<?= base_url("modulos/profeciones/editar.php");?>" class="btn btn-success">Nueva Profecion</a>
</div>

<table class="table table-striped">
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Categoria</th>
            <td></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach($profeciones as $profecion): ?>
            <tr>
                <td><?php echo htmlspecialchars($profecion->nombre);?></td>
                <td><?php echo htmlspecialchars($profecion->categoria);?></td>
                <td>
                    <a href="<?= base_url("modulos/profeciones/editar.php?codigo={$profecion->idx}");?>" class="btn btn-primary">Editar</a>
                </td>
            </tr>
            <?php endforeach;?>
    </tbody>
</table>