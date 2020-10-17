<?php
require "./persistencia/config.php";
require "./persistencia/conexion.php";
require './modelos/vehiculo.php';

class VehiculoTest extends \PHPUnit\Framework\TestCase{
    
    /** @test */ //Andres
    public function registrar_un_usuario_error(){
        $usuario = new Usuario("");

        $this->assertTrue($usuario->registrarUsuario());
    }

    /** @test */
    public function registrar_un_usuario(){
        $usuario = new Usuario("Juan","Laverde","1017273529","juan_laverde82162@elpoli.edu.co","1234","1234");

        $this->assertTrue($vehiculo->registrarUsuario());
    }
}

?>

