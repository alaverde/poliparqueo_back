<?php
require "./persistencia/config.php";
require "./persistencia/conexion.php";
require './modelos/usuario.php';

class UsuarioTest extends \PHPUnit\Framework\TestCase{
    
    /** @test */ //Juan Pablo
    public function registrar_un_usuario_error(){
        $usuario = new Usuario("");

        $this->assertTrue($usuario->registrarUsuario());
    }

    /** @test */
    public function registrar_un_usuario(){
        $usuario = new Usuario("Juan","Laverde","1017273529","juan_laverde82162@elpoli.edu.co","1234","1234");

        $this->assertTrue($usuario->registrarUsuario());
    }


     /** @test */ //Milton
     public function validar_usuario_fallido(){
        $usuario = new Usuario("");
        $usuario->validarUsuario();

        $this->assertEquals($usuario->validarUsuario());
    }
    
    /** @test */
    public function validar_usuario(){
        $usuario = new Usuario("juan_laverde82162@elpoli.edu.co","1234");
        $usuario->validarUsuario();

        $this->assertEquals($usuario->validarUsuario());
    }
}

?>

