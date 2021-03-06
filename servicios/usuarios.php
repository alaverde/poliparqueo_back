<?php
include "../persistencia/config.php";
include "../persistencia/conexion.php";
include "../modelos/usuario.php";
include "respuesta.php";

header('Content-Type: application/json');

$respuesta = new stdClass();
$respuesta->mensaje = "";
$respuesta->result = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    if (!isset($_GET['metodo']))
    {
        $respuesta->mensaje = "Debe indicar un método";
        Respuesta::send($respuesta, 401);
    }

    $metodo = $_GET['metodo'];

    switch ($metodo) {
        case 'validarUsuario':
            if(isset($_GET['correo']) && isset($_GET['contrasena'])){

                $respuesta = Usuario::validarUsuario($_GET['correo'], $_GET['contrasena']);
                Respuesta::send($respuesta, 200);
    
            }else{
                
                $respuesta->mensaje = "Debe indicar un usuario y contraseña";
                Respuesta::send($respuesta, 401);
            }
            break;
        
        default:
            $respuesta->mensaje = "Operación no definida";
            Respuesta::send($respuesta, 400);
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    if (!isset($_POST['metodo']))
    {
        $respuesta->mensaje = "Debe indicar un método";
        Respuesta::send($respuesta, 401);
    }

    $metodo = $_POST['metodo'];

    switch ($metodo) {
        case 'registrarUsuario':
            if(!(isset($_POST['nombres']) &&
                isset($_POST['apellidos'])&&
                isset($_POST['identificacion'])&&
                isset($_POST['correo'])&&
                isset($_POST['contrasena']))){
                
                $respuesta->mensaje = "Todos los campos son obligatorios";
                Respuesta::send($respuesta, 400);
            }
            
            $usuario = new Usuario(0);
            $usuario->setNombres($_POST['nombres']);
            $usuario->setApellidos($_POST['apellidos']);
            $usuario->setIdentificacion($_POST['identificacion']);
            $usuario->setCorreo($_POST['correo']);
            $usuario->setContrasena($_POST['contrasena']);

            $respuesta = Usuario::registrarUsuario($usuario);
            Respuesta::send($respuesta, 200);
            break;

        default:
            $respuesta->mensaje = "Operación no definida";
            Respuesta::send($respuesta, 400);
            break;
    }
}


?>