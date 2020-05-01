<?php
include "../persistencia/config.php";
include "../persistencia/conexion.php";
include "../modelos/usuario.php";
include "../modelos/registro_parqueadero.php";
include "../modelos/vehiculo.php";
include "../modelos/parqueadero.php";
//include "../modelos/tipo_registro.php";
include "respuesta.php";


header('Content-Type: application/json');

$respuesta = new stdClass();
$respuesta->mensaje = "";
$respuesta->result = false;

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    /*if (!isset($_GET['metodo']))
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
    }*/
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
        case 'registrarIngreso':
            if( !(isset($_POST['placa_vehiculo']) &&
                isset($_POST['identificacion'])) ){
                
                $respuesta->mensaje = "Todos los campos son obligatorios";
                Respuesta::send($respuesta, 200);
            }

            $vehiculo = new Vehiculo($_POST['placa_vehiculo']);
            if($vehiculo->getId() == 0){
                if(!$vehiculo->guardar()){
                    $respuesta->mensaje = "No se pudo agregar el vehiculo";
                    Respuesta::send($respuesta, 200);
                }
            }

            if(RegistroParqueadero::validarTipoUltimoRegistro($vehiculo,TipoRegistro::$ingreso)){
                $respuesta->mensaje = "Este vehículo ya se encuentra en el parqueadero";
                Respuesta::send($respuesta, 200);

            }

            $usuario = Usuario::consultarUsuario($_POST['identificacion']);
            if($usuario->getId() == 0){
                $respuesta->mensaje = "El usuario no se encuentra registrado en el sistema";
                Respuesta::send($respuesta, 200);
            }

            $parqueadero = new Parqueadero(1);

            $registro = new RegistroParqueadero(0, $vehiculo, $usuario, $parqueadero);

            $respuesta = RegistroParqueadero::registrarIngreso($registro);
            $respuesta->result = true;
            Respuesta::send($respuesta, 200);
            break;
          
        case 'registrarSalida':
            if( !(isset($_POST['placa_vehiculo']) &&
                isset($_POST['identificacion'])) ){
                
                $respuesta->mensaje = "Todos los campos son obligatorios";
                Respuesta::send($respuesta, 200);
            }

            $vehiculo = new Vehiculo($_POST['placa_vehiculo']);
            if($vehiculo->getId() == 0){
                    $respuesta->mensaje = "Este vehículo no se encuentra en el parqueadero";
                    Respuesta::send($respuesta, 200);
            }

            if(RegistroParqueadero::validarTipoUltimoRegistro($vehiculo,TipoRegistro::$salida)){
                $respuesta->mensaje = "Este vehículo no se encuentra en el parqueadero";
                Respuesta::send($respuesta, 200);

            }

            $usuario = Usuario::consultarUsuario($_POST['identificacion']);
            if($usuario->getId() == 0){
                $respuesta->mensaje = "El usuario no se encuentra registrado en el sistema";
                Respuesta::send($respuesta, 200);
            }

            $parqueadero = new Parqueadero(1);

            $registro = new RegistroParqueadero(0, $vehiculo, $usuario, $parqueadero);

            $respuesta = RegistroParqueadero::registrarSalida($registro);
            $respuesta->result = true;
            Respuesta::send($respuesta, 200);
            
            break;

        default:
            $respuesta->mensaje = "Operación no definida";
            Respuesta::send($respuesta, 400);
            break;

    }
}


?>