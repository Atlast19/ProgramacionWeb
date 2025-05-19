<?php include('partes/head.php');?>
<h2>Adivina</h2>
<h4>Adivina el numero que va a salir entre 1 y 5</h4>
<form method="GET" action="">
    <input type="number" name="random" style="width: 350px;" required id="numero" min="1" max="5" placeholder="Escriba un numero entre 1 y 5"
    value="<?=isset($_GET['random']) ? $_GET['random']:'';?>" />
    <button type="submit">Enviar</button>
</form>

<?php
if (isset($_GET['random'])) {
    $numero = $_GET['random'];
    $aleatorio = rand(1,5); 
    if ($numero == $aleatorio) {
        echo "<h3>Felicidades el numero correcto si es: $aleatorio</h3>";
        
    }else{
        echo "<h3>el numero correcto era: $aleatorio</h3>";
        
    }
}
?>
<?php include('partes/footer.php');?>