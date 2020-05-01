<?php 

class Parqueadero {
    private $id;
    private $nombre;
    private $cantidadPlazas;
    
    function __construct($id){
        $this->id = $id;
        $this->consultar();
    }

    function getId(){
        return $this->id;
    }

    function getPlaca(){
        return $this->identificacion;
    }

    function getCantidadPlazas(){
        return $this->cantidadPlazas;
    }

    function consultar(){
        $conexion = Conexion::connect(Config::getConfig());
        $sql = $conexion->prepare("SELECT * FROM parqueadero WHERE id=:id");
        $sql->bindValue(':id', $this->id);
        $sql->execute();
        $result = $sql->fetch(PDO::FETCH_ASSOC);

        if($result !== false){
            $this->nombre = $result["nombre"];
            $this->cantidadPlazas = $result["cantidad_plazas"];
        }else{
            $this->nombre = "";
            $this->cantidadPlazas = 0;
        }
    }
}

?>