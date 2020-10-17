<?php
//require_once "./persistencia/config.php";
//require_once "./persistencia/conexion.php";
require_once './modelos/parqueadero.php';

class ParqueaderoTest extends \PHPUnit\Framework\TestCase{
      /** @test */
        public function consultar_parqueadero_con_el_ID(){
            $parqueadero = new Parqueadero(1);
            $id = $parqueadero->getId(); 
            $this->assertEquals($id,$parqueadero->consultar());
        }
        /** @test */
        public function consultar_parqueadero_con_el_ID_Falla(){
            $parqueadero = new Parqueadero(5);
            $id = 5;
            $this->assertEquals($id,$parqueadero->consultar());
        }

}
?>