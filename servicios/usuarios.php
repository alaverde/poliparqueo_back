<?php
include "../persistencia/config.php";
include "../persistencia/conexion.php";
header('Content-Type: application/json');

$conexion = connect($db);

if ($_SERVER['REQUEST_METHOD'] == 'GET')
{
    $respuesta = new stdClass();
    $respuesta->mensaje = "";
    $respuesta->result = false;

    if (isset($_GET['metodo']))
    {
        $metodo = $_GET['metodo'];



        if($metodo == "validarUsuario"){
            if(isset($_GET['correo']) && isset($_GET['contrasena'])){
                //Mostrar un post
                $sql = $conexion->prepare("SELECT * FROM usuario WHERE correo=:correo AND clave_acceso=:contrasena");
                $sql->bindValue(':correo', $_GET['correo']);
                $sql->bindValue(':contrasena', $_GET['contrasena']);
                $sql->execute();
                header("HTTP/1.1 200 OK");
                $result = $sql->fetch(PDO::FETCH_ASSOC);

                if($result !== false){
                    $respuesta->result = true;
                    $respuesta->permisos = $result["permisos"];
                    $respuesta->id = $result["id"];
                }else{
                    $respuesta->result = false;
                }
                echo json_encode(  $respuesta  );
                exit();
            }else{
                $respuesta->mensaje = "Debe indicar un usuario y contraseña";
                echo json_encode($respuesta);
                http_response_code(401);
                exit();
            }
        }
    }
    else {
        $respuesta->mensaje = "Debe indicar un método";
        echo json_encode($respuesta);
        http_response_code(401);
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $respuesta = new stdClass();
    $respuesta->mensaje = "";
    $respuesta->result = false;

    if (isset($_POST['metodo']))
    {
        $metodo = $_POST['metodo'];

        if($metodo == "registrarUsuario"){

            if(!(isset($_POST['nombres']) &&
                isset($_POST['apellidos'])&&
                isset($_POST['identificacion'])&&
                isset($_POST['correo'])&&
                isset($_POST['contrasena']))){
                $respuesta->mensaje = "Todos los campos son obligatorios";
                echo json_encode($respuesta);
                http_response_code(401);
                exit();
            }

            //Mostrar un post
            $sql = $conexion->prepare("INSERT INTO `usuario`( `correo`, `clave_acceso`, `identificacion`, `nombres`, `apellidos`, `permisos`) VALUES (:correo, :contrasena, :identificacion, :nombres, :apellidos, :permisos)");
            $sql->bindValue(':correo', $_POST['correo']);
            $sql->bindValue(':contrasena', $_POST['contrasena']);
            $sql->bindValue(':identificacion', $_POST['identificacion']);
            $sql->bindValue(':nombres', $_POST['nombres']);
            $sql->bindValue(':apellidos', $_POST['apellidos']);
            $sql->bindValue(':permisos', 2);
            $sql->execute();
            header("HTTP/1.1 200 OK");
            
            if($sql->rowCount() > 0){
                $respuesta->result = true;
            }else{
                $respuesta->result = false;
            }
            echo json_encode(  $respuesta  );
            exit();
        }
    }
    else {
        $respuesta->mensaje = "Debe indicar un método";
        echo json_encode($respuesta);
        http_response_code(401);
        exit();
    }
}


?>