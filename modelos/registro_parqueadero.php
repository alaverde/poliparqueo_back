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

    static function registrarSalida($registro){
        $conexion = Conexion::connect(Config::getConfig());
        $sql = $conexion->prepare("INSERT INTO `registro`(`vehiculo`, `conductor`, `parqueadero`, `fecha`, `tipo_registro`) VALUES (:vehiculo,:conductor,:parqueadero,now(),:tipo_registro)");
        $sql->bindValue(':vehiculo', $registro->getVehiculo()->getId());
        $sql->bindValue(':conductor', $registro->getConductor()->getId());
        $sql->bindValue(':parqueadero', $registro->getParqueadero()->getId());
        $sql->bindValue(':tipo_registro', TipoRegistro::$salida);
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
    
    static function validarTipoUltimoRegistro($vehiculo,$tipoRegistro){
        $conexion = Conexion::connect(Config::getConfig());
        $sql = $conexion->prepare("SELECT * FROM registro WHERE vehiculo=:vehiculo ORDER BY fecha DESC");
        $sql->bindValue(':vehiculo', $vehiculo->getId());
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        /* var_dump($result); imprime lo que tiene la variable $result*/
        if($result["tipo_registro"] == $tipoRegistro){
            return true;
        }
        return false;

    }

    static function consultarPlazasLibres(){
        $conexion = Conexion::connect(Config::getConfig());
        $sql = $conexion->prepare("SELECT *  FROM parqueadero");
        $sql->execute();
        $parqueaderos = $sql->fetchAll(PDO::FETCH_ASSOC);

        $plazasLibres = array();
        foreach ($parqueaderos as $row => $parquadero) {

            $infoParqueadero = new stdClass();
            $infoParqueadero->parqueadero = $parquadero["id"];
            $infoParqueadero->nombre = $parquadero["nombre"];

            $conexion = Conexion::connect(Config::getConfig());
            $sql = $conexion->prepare("SELECT count(id) ingresos FROM registro WHERE tipo_registro=1 AND parqueadero=:parqueadero");
            $sql->bindValue(':parqueadero',$infoParqueadero->parqueadero);
            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            $ingreso = $result['ingresos'];

            $conexion = Conexion::connect(Config::getConfig());
            $sql = $conexion->prepare("SELECT count(id) salidas FROM registro WHERE tipo_registro=2 AND parqueadero=:parqueadero");
            $sql->bindValue(':parqueadero',$infoParqueadero->parqueadero);
            $sql->execute();
            $result = $sql->fetch(PDO::FETCH_ASSOC);
            $salidas = $result['salidas'];

            $totalOcupados = $ingreso - $salidas;
            $parqueaderoLibre = new Parqueadero($infoParqueadero->parqueadero);
            $disponibles = $parqueaderoLibre->getCantidadPlazas() - $totalOcupados;

            $infoParqueadero->plazas_libres = $disponibles;

            array_push($plazasLibres, $infoParqueadero);
        }

        return $plazasLibres;
    }

    static function consultarHistorial($usuario){
        
        $where = "";

        if($usuario != 0){
            $where = "WHERE registro.conductor = $usuario";
        }

        $script = "SELECT 
                        ".$usuario." id,
                        registro.fecha,
                        usuario.identificacion,
                        usuario.nombres,
                        parqueadero.nombre parqueadero,
                        vehiculo.placa,
                        CASE 
                            WHEN registro.tipo_registro = 1 THEN 'INGRESO'
                            ELSE 'SALIDA'
                        END accion
                        FROM registro
                            LEFT JOIN usuario ON usuario.id = registro.conductor
                            LEFT JOIN parqueadero ON parqueadero.id = registro.parqueadero
                            LEFT JOIN vehiculo ON vehiculo.id = registro.vehiculo 
                        ". $where ."
                        ORDER BY fecha DESC";

        $conexion = Conexion::connect(Config::getConfig());
        $sql = $conexion->prepare($script);
        $sql->execute();
        $historial = $sql->fetchAll(PDO::FETCH_ASSOC);

        return $historial;
    }
}

?>