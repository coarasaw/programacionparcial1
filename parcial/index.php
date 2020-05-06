<?php

/*
* Ejemplo Parcial
*/
require_once 'jwtHeader.php';
include_once './class/response.php'; // Clase respuesta.
include_once './class/clases.php';  // Verificará si el archivo ya ha sido incluido.
include_once './class/datos.php';
include_once './class/materias.php';
include_once './class/ventas.php';


//include_once 'generaJMT.php';

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '';
//$path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO']  : '';  //No se bien como funciona

/* echo $metodo;
var_dump($_SERVER); */

$response = new Response();
$archivo = 'file/users.txt';
$archivoJSON = 'file/materias.json';
$archivoVenta = 'file/ventas.txt';

use \Firebase\JWT\JWT;  // requerimiento de la libreria 


switch ($method) {
    case 'POST':
        switch ($path) {
            case '/usuario': # email, clave
                
                $email = $_POST['email'] ?? null;
                $clave = $_POST['clave'] ?? null;
                
                if (isset($email) && isset($clave)) {
                        $usuarios = array();
                        $unUsuario = new Usuario($email,$clave);
                        //var_dump($unUsuario);
                        //die();
                        array_push($usuarios,$unUsuario);
                        $rta = Datos::guardarDatos($archivo,$usuarios);
                        $response->data = $rta;
                        echo json_encode($response);

                } else {
                        $response->data = 'Faltan datos';
                        $response->status = 'fail';
                        echo json_encode($response);
                }             
                break;
            case '/login': #​Recibe emial y clave y si son correctos devuelve un JWT, de lmateriaso contrario informar lo sucedido.

                $email = $_POST['email'] ?? null;
                $clave = $_POST['clave'] ?? null;

                if (isset($email) && isset($clave)) {
                    $usuariosDesearializados = Datos::leer($archivo);
                    
                    foreach ($usuariosDesearializados as $usuario) {
                        //echo $usuario->BuscarUsuario($clave,$nombre) . "\n";
                        if($usuario->clave == $clave && $usuario->email == $email){
                            $encontroUsuario = $usuario;
                            break;
                        }else{
                            $response->status = 'Usuario-Clave Erroneo';
                            $encontroUsuario = "NO ENCONTRO USUARIO";
                        }          
                    }
                    if ($encontroUsuario != "NO ENCONTRO USUARIO") {
                        //$encontrarJMT = "Genero JMT";
                        $payload = array(
                            "iss" => "http://example.org",
                            "aud" => "http://example.com",
                            "iat" => 1356999524,               
                            "nbf" => 1357000000,
                            "email" => $email,
                            "clave" => $clave
                        );
                
                        $jwt = JWT::encode($payload, $key);
                        //Clave generada
                        //"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLm9yZyIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUuY29tIiwiaWF0IjoxMzU2OTk5NTI0LCJuYmYiOjEzNTcwMDAwMDAsImVtYWlsIjoiY29hcmFzYXdAZ21haWwuY29tIiwiY2xhdmUiOiIxMjM0In0.Isn15Tpw6VHDKvfvsHpdFJkpig16s6qKs8fc_5zUO0s"
                        $encontrarJMT = $jwt;
                    }else{
                        $encontrarJMT = "NO ENCONTRO USUARIO";
                    } 
                    
                    $rta = $encontrarJMT;
                    $response->data = $rta;
                    echo json_encode($response);
                }    
                else {
                    $response->data = 'Usuario/Clave Erroneo';
                    $response->status = 'fail';
                    echo json_encode($response);
                }    
                break;
                    
            case '/materia': # materia:​ Recibe nombre, cuatrimestre y lo guarda en el archivo .xxx. Agregar un id único  para cada materia.

                $headers = getallheaders(); //Leeo toda mi cabecera
                $miToken = $headers["token"] ?? 'No mando Token'; // Si se genero el Token aca lo obtengo de la cabecera
                //print_r($headers);
                //print_r($miToken);
                try {
                    $decoded = JWT::decode($miToken, $key, array('HS256'));
                    // print_r($decoded); 
                    //print $obj->{'foo-bar'}; // 12345
                    //die();
                    $validarUsuario = "true";
                    if ($validarUsuario == "true") {
                        //print_r("grabar JSON");
                        $nombre = $_POST['nombre'] ?? null;
                        $cuatrimestre = $_POST['cuatrimestre'] ?? null;
                        

                        if (isset($nombre) && isset($cuatrimestre)) {
                            $id = $nombre . '-' . time();
                            $producto = new Materias($id,$nombre, $cuatrimestre);
                            $rta = $producto->saveMateria($archivoJSON);
                            
                            $response->data = $rta;
                            echo json_encode($response);
                        } 
                        else {
                                $response->data = 'Faltan datos';
                                $response->status = 'fail';
                                echo json_encode($response);
                        }                  
                    }
                    //die();
                } 
                catch (\Throwable $th) {
                    //echo $th->getMessage() . " Error JWT";
                    //  aca va respuesta de Error
                    $response->data = ' Error JWT';
                    $response->status = 'fail';
                    echo json_encode($response);
                }   
                break;
            case '/ventas': # Guardar las ventas / recive id y cantidad del producto y usuario 
                $headers = getallheaders(); //Leeo toda mi cabecera
                $miToken = $headers["token"] ?? 'No mando Token'; 
                //var_dump($headers);
                //var_dump($miToken);
                //var_dump($key);
                //die();
                try {
                    $decoded = JWT::decode($miToken, $key, array('HS256'));
                    $varNombre = $decoded->{'nombre'};
                    //var_dump($varNombre);
                    //die();
                    //Realizamos la venta si hay stock
                    $nombre = $_POST['nombre'] ?? null;
                    $cuatrimestre = $_POST['cuatrimestre'] ?? null;
                   

                    if (isset($id_producto) && isset($cantidad) && isset($ususario)) {
                        if ($ususario=='user') {
                            $lista = Datos::leerJSON($archivoJSON);
                            $encontroStock = "false";
                            foreach ($lista as $value) {
                                if ($value->id == $id_producto && $value->stock >= $cantidad) {
                                    //$value->stock = $value->stock - $cantidad;
                                    $encontroStock = "true";
                                    //Se debe actualizar el stock
                                    break;
                                }
                            }
                            if ($encontroStock == "true") {
                                    $ventas = array();
                                    $unaVenta = new Venta($id_producto,$cantidad,$varNombre,'user');
                                    array_push($ventas,$unaVenta);
                                    $rta = Datos::guardarDatos($archivoVenta,$ventas);
                                    $response->data = $rta;
                            }else{
                                $rta = 'No hay Stock';
                                $response->status = 'fail';
                            }
                        }else{
                            $rta = 'No es user!!!';
                            $response->status = 'fail';
                        }
                    }else{
                        $rta = 'Faltan datos para la ventas';
                        $response->status = 'fail';
                    }
                    
                    $response->data = $rta;
                    echo json_encode($response);
                } 
                catch (\Throwable $th) {
                    $response->data = ' Error JMT POST /ventas';
                    $response->status = 'fail';
                    echo json_encode($response);
                }
                break;    
            default:
                $response->data = 'Path no soportado en POST';
                $response->status = 'fail';
                echo json_encode($response);
                break;
        }
    break;

    case 'GET':
        switch ($path) {
            case '/stock': #  Muestra la lista de Productos.
                
                $headers = getallheaders(); //Leeo toda mi cabecera
                $miToken = $headers["token"] ?? 'No mando Token'; 
                //var_dump($miToken);
                //die();
                try {
                    $decoded = JWT::decode($miToken, $key, array('HS256'));
                    $rta = Datos::leerJSON($archivoJSON);
                    //var_dump($rta);
                    $response->data = $rta;
                    echo json_encode($response);
                } catch (\Throwable $th) {
                    //echo $th->getMessage() . " Error JWT";
                    $response->data = ' Error JMT GET /stock';
                    $response->status = 'fail';
                    echo json_encode($response);
                }

                break;
            case '/ventas': # Si es admin muestra listado con todas las ventas / si es user solo las ventas de dicho usuario
                    
                    $headers = getallheaders(); //Leeo toda mi cabecera
                    $miToken = $headers["token"] ?? 'No mando Token'; // Si se genero el Token aca lo obtengo de la cabecera
                    
                    try {
                        $decoded = JWT::decode($miToken, $key, array('HS256'));
                        $varNombre = $decoded->{'nombre'}; //obtener nombre y clave del Token
                        $varClave = $decoded->{'clave'};
                        $usuariosDesearializados = Datos::leer($archivo);
                        
                        foreach ($usuariosDesearializados as $usuario) {
                            
                            if($usuario->clave == $varClave && $usuario->nombre == $varNombre && $usuario->tipo == 'admin'){
                                $validarUsuario = "admin";
                                break;
                            }else{
                                $validarUsuario = "user";
                            } 
                        }  

                        if ($validarUsuario == "admin") {
                            $ventasDesearializados = Datos::leer($archivoVenta);
                            $response->data = $ventasDesearializados;
                        } else {
                            $ventasDesearializados = Datos::leer($archivoVenta);

                            foreach ($ventasDesearializados as $venta) {
                                if ($venta->nombreUsuario == $varNombre ) {
                                    $usuariosVenta = array();
                                    array_push($usuariosVenta,$venta);
                                }
                            }
                            $response->data = $usuariosVenta;
                        }
                    } 
                    catch (\Throwable $th) {
                        $response->data = ' Error JWT en GET ventas';
                        $response->status = 'fail';
                    }      
                    echo json_encode($response); 
                    break;
            default:
                $response->data = 'Path no soportado en GET';
                $response->status = 'fail';
                echo json_encode($response);
                break;
        }
    break;    
            
    default:
        $response->data = 'Metodo no soportado';
        $response->status = 'fail';
        echo json_encode($response);
        break;
}