<?php 
include('partes/head.php');

$valor1 = $_GET['valor1'];
$valor2 = $_GET['valor2'];
$operacion = $_GET['operacion'];

$resultado = 0;

switch ($operacion) {
    case 'suma':
        $resultado = $valor1 + $valor2;
        break;

    case 'resta':
        $resultado = $valor1 - $valor2;
        break;

    case 'multiplicacion':
        $resultado = $valor1 * $valor2;
        break;

    case 'divicion':
        if ($valor2 != 0) {
            $resultado = $valor1 / $valor2;
        }
        else {
            echo "No se puede dividir entre 0";
            exit();
        }
        break;
    
    default:
        echo "Operacion no valida";
        exit();
}

if (is_numeric($resultado)) {
    $resultado = number_format($resultado, 2);
}
?>
<h2>Resultado de la calculadora</h2>
<p>El resultado de la <?php echo $operacion;?> entre <?php echo $valor1; ?> y <?php echo $valor2;?> es: <strong><?php echo $resultado;?></strong></p>

<a href="Calculadora.php">Volver a la calculadora</a>



<?php include('partes/footer.php');?>