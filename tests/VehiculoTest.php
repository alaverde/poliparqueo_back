<?php
require "./persistencia/config.php";
require "./persistencia/conexion.php";
require './modelos/vehiculo.php';

class VehiculoTest extends \PHPUnit\Framework\TestCase{
    
    /** @test */ //Andres
    public function guardar_un_vehiculo_error(){
        $vehiculo = new Vehiculo("");

        $this->assertTrue($vehiculo->guardar());
    }

    /** @test */
    public function guardar_un_vehiculo(){
        $vehiculo = new Vehiculo("MNB645");

        $this->assertTrue($vehiculo->guardar());
    }

    
    /** @test */ //Karolayn 
    public function consultar_id_vehiculo_fallo(){
        $vehiculo = new Vehiculo("KMI112");
        $vehiculo->guardar();
        $id = 1;

        $this->assertEquals($id,$vehiculo->consultarId());
    }
    
    /** @test */
    public function consultar_id_vehiculo(){
        $vehiculo = new Vehiculo("KMI112");
        $vehiculo->guardar();
        $id = $vehiculo->getId();
        $vehiculo2 = new Vehiculo("KMI112");

        $this->assertEquals($id,$vehiculo2->consultarId());
    }

}

?>

