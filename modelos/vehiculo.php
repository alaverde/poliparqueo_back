<?php 

class Vehiculo {
    private $id;
    private $placa;
    
    function __construct($placa){
        $this->placa = $placa;
        $this->consultarId();
    }

    function getId(){
        return $this->id;
    }

    function getPlaca(){
        return $this->placa;
    }

    function consultarId(){
        $conexion = Conexion::connect(Config::getConfig());
        $sql = $conexion->prepare("SELECT * FROM vehiculo WHERE placa=:placa");
        $sql->bindValue(':placa', $this->placa);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);

        if($result !== false){
            $this->id = $result["id"];
        }else{
            $this->id = 0;
        }
    }

    function guardar(){
        $conexion = Conexion::connect(Config::getConfig());
        $sql = $conexion->prepare("INSERT INTO `vehiculo`(`placa`) VALUES (:placa)");
        $sql->bindValue(':placa', $this->getPlaca());
        $sql->execute();

        if($sql->rowCount() > 0){
            $this->id = $conexion->lastInsertId();
            return true;
        }

        return false;
    }
}

?>