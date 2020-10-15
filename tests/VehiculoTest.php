<?php
require "./persistencia/config.php";
require "./persistencia/conexion.php";
require './modelos/vehiculo.php';

class VehiculoTest extends \PHPUnit\Framework\TestCase{
    
    /** @test */
    public function guardar_un_vehiculo_error(){
        $vehiculo = new Vehiculo("147852369");

        $this->assertTrue($vehiculo->guardar());
    }

    /** @test */
    public function guardar_un_vehiculo(){
        $vehiculo = new Vehiculo("MNB645");

        $this->assertTrue($vehiculo->guardar());
    }
}

?>

