<?php
include("../../libreria/principal.php");

define("PAGINA_ACTUAL","personajes");
Plantilla::aplicar();

$personajes = DBx::list("personajes");

?>
<h4>Lista de los Personajes</h4>

<div class="text-end mb-3">
    <a href="<?= base_url("modulos/personajes/editar.php");?>" class="btn btn-success">Nuevo Personaje</a>
</div>

<table class="table table-striped">
<thead>
    <tr>
        <th>Nombre</th>
        <th>Edad</th>
        <th>Experienica </th>
        <th>Profecion</th>
        <td></td>
    </tr>
</thead>
<tbody>
    <?php foreach($personajes as $personaje): ?>
        <tr>
            <td><?php echo htmlspecialchars($personaje->nombre); ?></td>
            <td><?php echo htmlspecialchars($personaje->edad()); ?></td>
            <td><?php echo htmlspecialchars($personaje->nivel_experiencia); ?></td>
            <td><?php echo htmlspecialchars($personaje->profecion); ?></td>
            <td>
                <a href="<?=base_url("modelos/personajes/editar.php?codigo={$personaje->idx}"); ?>" class="btn btn-primary">Editar</a>
            </td>
        </tr>
        <?php endforeach;?>
</tbody>
</table>