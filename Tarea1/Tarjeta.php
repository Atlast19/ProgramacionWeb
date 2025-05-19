<?php 
$nombre ='Pedro Leandro';
$apellido = 'Aponte Heredia';
$edad = 19;
$carrera = 'Desarrollo de software';
$universidad = 'ITLA';
$mensaje = ($edad >= 18) ? 'Es mayor de edad':'Es menor de edad';
?>
<?php include('partes/head.php');?>
<h2>tarjeta</h2>
<table border="1">
    <tr>
        <th>Nombre</th><td><?php echo $nombre;?></td>
    </tr>
    <tr>
        <th>Apellido</th><td><?php echo $apellido;?></td>
    </tr>
    <tr>
        <th>Edad</th><td><?php echo $edad;?></td>
    </tr>
    <tr>
        <th>Carreara</th><td><?php echo $carrera;?></td>
    </tr>
    <tr>
        <th>Universidad</th><td><?php echo $universidad;?></td>
    </tr>
</table>

<h3><?php echo $mensaje;?></h3>
<?php include('partes/footer.php');?>