<?php
class Respuesta {
    static function send($respuesta, $codigo){
        echo json_encode($respuesta);
        http_response_code($codigo);
        exit();
    }
}


?>