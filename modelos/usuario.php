<?php 

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

    static function consultarUsuario($identificacion){
        $conexion = Conexion::connect(Config::getConfig());
        $sql = $conexion->prepare("SELECT * FROM usuario WHERE identificacion=:identificacion");
        $sql->bindValue(':identificacion', $identificacion);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);

        if($result !== false){
            $usuario = new Usuario($result["id"]);
            $usuario->setIdentificacion($result["identificacion"]);
            $usuario->setNombres($result["nombres"]);
            $usuario->setApellidos($result["apellidos"]);
            $usuario->setCorreo($result["correo"]);
            $usuario->setContrasena($result["clave_acceso"]);

            return $usuario;
        }

        return new Usuario($result["id"]);
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
            $respuesta->nombres = $result["nombres"];
            $respuesta->parqueadero_asignado = $result["parqueadero_asignado"];
        }else{
            $respuesta->result = false;
        }

        return $respuesta;
    }
}

?>