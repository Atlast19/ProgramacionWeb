<?php
class Personajes{
    public $idx = '';
    public $identificacion = '';
    public $nombre = '';
    public $apellido = '';
    public $fecha_nacimiento = '';
    public $foto = '';
    public $profecion = '';
    public $nivel_experiencia = 0;

    public function edad(){
        if(empty($this->fecha_nacimiento)){
            return 0;
        }
        $fecha_nacimiento = strtotime($this->fecha_nacimiento);
        $fecha_actual = time();
        $edad = date('Y', $fecha_actual) - date('Y', $fecha_nacimiento);
        if(date('md',$fecha_actual) < date ('md',$fecha_nacimiento)){
            $edad--;
        }
        return $edad;
    }


    public function __construct($data =[]){
        if(is_object($data)){
            $data = (array)$data;
        }

        foreach ($data as $key => $value) {
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }
    }
}


class Profeciones{
    public $idx = ''; 
    public $codigo = '';
    public $nombre = '';
    public $categoria = '';
    public $salario_mensual = 0;

    public function __construct($data =[]){
        if(is_object($data)){
            $data = (array)$data;
        }

        foreach ($data as $key => $value) {
            if(property_exists($this, $key)){
                $this->$key = $value;
            }
        }
    }
}

?>