<?php
class Plantilla{

    static $instancia = null;
    public static function aplicar(){
        if(self::$instancia == null){
            self::$instancia = new Plantilla();
    }
    return self::$instancia;
}
    function __construct(){

        $pagina_actual = (defined("PAGINA_ACTUAL") ? PAGINA_ACTUAL : 'inicio');
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
            <title>Document</title>
        </head>
        <body>
            <div class="contenedor">
                <div>
                    <h1>Mundo Barbie</h1>
                </div>

                <div class ="divMenu">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a href="<?= base_url();?>" class="nav-link"<?= $pagina_actual == 'inicio' ? 'active' : '';?>  aria-current="page">Inicio</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('modulos/personajes/lista_per.php');?>" <?= $pagina_actual == 'personajes' ? 'active' : '';?> class="nav-link">Personajes</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('modulos/profeciones/lista.php');?>" <?= $pagina_actual == 'profeciones' ? 'active' : '';?> class="nav-link">Profeciones</a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('modulos/reportes/menu.php');?>" <?= $pagina_actual == 'estadicticas' ? 'active' : '';?> class="nav-link">Estadisticas</a>
                        </li>
                    </ul>
                </div>


                <div class="divContenido" style="min-height: 400px;">

                
        <?php
    }
    function __destruct(){
        ?>
        </div>

                <div class="footer">
                    <hr>
                    <p>footer Mundo Barbie</p>
                </div>
            </div>
        </body>
        </html>
        <?php
    } 
}
?>