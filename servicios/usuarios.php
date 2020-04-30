<?php
include "../persistencia/config.php";
include "../persistencia/conexion.php";
include "respuesta.php";

header('Content-Type: application/json');

class Usuario {
    private $id;
    private $identificacion;
    private $nombres;
    private $apellidos;
    private $correo;
    private $contrasena;
    
    function __construct($id){
        $this->id = $id;
    }

    function getId(){
        return $this->id;
    }

    function getIdentificacion(){
        return $this->identificacion;
    }

    function setIdentificacion($identificacion){
        $this->identificacion = $identificacion;
    }

    function setNombres($nombres){
        $this->nombres = $nombres;
    }

    function getNombres(){
        return $this->nombres;
    }

    function setApellidos($apellidos){
        $this->apellidos = $apellidos;
    }

    function getApellidos(){
        return $this->apellidos;
    }

    function setCorreo($correo){
        $this->correo = $correo;
    }

    function getCorreo(){
        return $this->correo;
    }

    function setContrasena($contrasena){
        $this->contrasena = $contrasena;
    }

    function getContrasena(){
        return $this->contrasena;
    }

    static function registrarUsuario($usuario){
        $conexion = Conexion::connect(Config::getConfig());
        $sql = $conexion->prepare("INSERT INTO `usuario`( `correo`, `clave_acceso`, `identificacion`, `nombres`, `apellidos`, `permisos`) VALUES (:correo, :contrasena, :identificacion, :nombres, :apellidos, :permisos)");
        $sql->bindValue(':correo', $usuario->getCorreo());
        $sql->bindValue(':contrasena', $usuario->getContrasena());
        $sql->bindValue(':identificacion', $usuario->getIdentificacion());
        $sql->bindValue(':nombres', $usuario->getNombres());
        $sql->bindValue(':apellidos', $usuario->getApellidos());
        $nivelUsuarioParqueadero = 2;
        $sql->bindValue(':permisos', $nivelUsuarioParqueadero);
        $sql->execute();

        $respuesta = new stdClass();
        $respuesta->mensaje = "";
        $respuesta->result = false;

        if($sql->rowCount() > 0){
            $respuesta->result = true;
        }else{
            $respuesta->result = false;
        }

        return $respuesta;
    }

    static function validarUsuario($correo, $contrasena){
        $conexion = Conexion::connect(Config::getConfig());
        $sql = $conexion->prepare("SELECT * FROM usuario WHERE correo=:correo AND clave_acceso=:contrasena");
        $sql->bindValue(':correo', $correo);
        $sql->bindValue(':contrasena', $contrasena);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);

        $respuesta = new stdClass();
        $respuesta->mensaje = "";
        $respuesta->result = false;

        if($result !== false){
            $respuesta->result = true;
            $respuesta->permisos = $result["permisos"];
            $respuesta->id = $result["id"];
        }else{
            $respuesta->result = false;
        }

        return $respuesta;
    }
}

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