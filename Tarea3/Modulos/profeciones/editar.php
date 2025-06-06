<?php
include("../../libreria/principal.php");
define("PAGINA_ACTUAL","profeciones");


if($_POST){
    $profeciones = new Profeciones($_POST);

    DBx::save("profeciones", $profeciones);
    header("Location: " . base_url("modulos/profeciones/lista.php"));
    exit;
}


Plantilla::aplicar();
$profeciones = new Profeciones();

if(isset($_GET['codigo'])){
    $tmp = DBx::get("profeciones", $_GET['codigo']);

    if($tmp){
        $profeciones = $tmp;
    }
}else{
    $profeciones = new Profeciones();
}

?>



<h3>Editar Profeciones</h3>

<form method="post" action="<?= $_SERVER['REQUEST_URI']?>">
<div class="mb-3">
    <label for="codigo" class="form-label">Codigo</label>
    <input type="text" class="form-control" id="idx" name="idx" value="<?= htmlspecialchars($profeciones->idx);?>" readonly>
</div>

<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($profeciones->nombre);?>">
</div>

<div class="mb-3">
    <label for="categoria" class="form-label">Categoria</label>
    <input type="text" class="form-control" id="categoria" name="categoria" value="<?= htmlspecialchars($profeciones->categoria);?>">
</div>

<div class="mb-3">
    <label for="Salario" class="form-label">Salario Mensual</label>
    <input type="number" class="form-control" id="salario_mensual" name="salario_mensual" value="<?= htmlspecialchars($profeciones->salario_mensual);?>">
</div>

<button type="submit" class="btn btn-primary">Guardar</button>
<a href="<?= base_url("modulos/profeciones/lista.php");?>" class="btn btn-secondary">Cancelar</a>
</form>