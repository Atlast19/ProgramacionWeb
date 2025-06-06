<?php
include("../../libreria/principal.php");
define("PAGINA_ACTUAL","personajes");

if($_POST){
    $personajes = new Personajes($_POST);

    DBx::save("personajes", $personajes);
    header("Location: " . base_url("modelos/personajes/lista.php"));
    exit;
}

Plantilla::aplicar();

$personajes = new Personajes();

if(isset($_GET['codigo'])){
    $tmp = DBx::get("personajes", $_GET['codigo']);
    if($tmp){
        $personajes = $tmp;
    }
}else{
    $personajes = new Personajes();
}
?>

<h3>Editar Personaje</h3>

<form method="post" action="<?= $_SERVER['REQUEST_URI']?>">
<div class="mb-3">
    <label for="codigo" class="form-label">Codigo</label>
    <input type="text" class="form-control" id="idx" name="idx" value="<?= htmlspecialchars($personajes->idx);?>" readonly>
</div>

<div class="mb-3">
    <label for="identificacion" class="form-label">Identificacion</label>
    <input type="number" class="form-control" id="identificacion" name="identificacion" value="<?= htmlspecialchars($personajes->identificacion);?>">
</div>

<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($personajes->nombre);?>">
</div>

<div class="mb-3">
    <label for="apellido" class="form-label">Apellido</label>
    <input type="text" class="form-control" id="apellido" name="apellido" value="<?= htmlspecialchars($personajes->apellido);?>">
</div>

<div class="mb-3">
    <label for="fecha_nacimiento" class="form-label">Fehca Nacimiento</label>
    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($personajes->fecha_nacimiento);?>">
</div>

<div class="mb-3">
    <label for="foto" class="form-label">Foto</label>
    <input type="text" class="form-control" id="foto" name="foto" value="<?= htmlspecialchars($personajes->foto);?>">
</div>

<div class="mb-3">
    <label for="fecha_nacimiento" class="form-label">profecion</label>
    <select  class="form-select" name="profecion" id="profecion" required>
        <option value="">Seleccione una Pofecion</option>
        <?php
        $profeciones = DBx::list("profeciones");
        foreach($profeciones as $prof): ?>
        <option value="<?= htmlspecialchars($prof->idx);?>" <?=$personajes->profecion == $prof->idx ? 'selected': '';?>>
            <?=htmlspecialchars($prof->nombre);?>
        </option>
        <?php endforeach;?>
    </select>
</div>

<div class="mb-3">
    <label for="nivel_experiencia" class="form-label">Nivel de Experiencia</label>
    <input type="number" class="form-control" id="nivel_experiencia" name="nivel_experiencia" value="<?= htmlspecialchars($personajes->nivel_experiencia);?>">
</div>

<button type="submit" class="btn btn-primary">Guardar</button>
<a href="<?= base_url("modulos/personajes/lista_per.php");?>" class="btn btn-secondary">Cancelar</a>
</form>