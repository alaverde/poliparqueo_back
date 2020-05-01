<?php 
include "tipo_registro.php";

class RegistroParqueadero {
    private $id;
    private $fecha;
    private $vehiculo;
    private $conductor;
    private $parqueadero;
    
    function __construct($id, $vehiculo, $conductor, $parqueadero){
        $this->id = $id;
        if($id == 0){
            $this->vehiculo = $vehiculo;
            $this->conductor = $conductor;
            $this->parqueadero = $parqueadero;
        }
    }

    function getId(){
        return $this->id;
    }

    function getFecha(){
        return $this->fecha;
    }

    function getVehiculo(){
        return $this->vehiculo;
    }

    function getConductor(){
        return $this->conductor;
    }

    function getParqueadero(){
        return $this->parqueadero;
    }

    static function registrarIngreso($registro){
        $conexion = Conexion::connect(Config::getConfig());
        $sql = $conexion->prepare("INSERT INTO `registro`(`vehiculo`, `conductor`, `parqueadero`, `fecha`, `tipo_registro`) VALUES (:vehiculo,:conductor,:parqueadero,now(),:tipo_registro)");
        $sql->bindValue(':vehiculo', $registro->getVehiculo()->getId());
        $sql->bindValue(':conductor', $registro->getConductor()->getId());
        $sql->bindValue(':parqueadero', $registro->getParqueadero()->getId());
        $sql->bindValue(':tipo_registro', TipoRegistro::$ingreso);
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

    /*static function validarUsuario($correo, $contrasena){
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
            $respuesta->nombres = $result["nombres"];
        }else{
            $respuesta->result = false;
        }

        return $respuesta;
    }*/
}

?>