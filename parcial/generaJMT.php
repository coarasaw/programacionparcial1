<?
include 'vendor/autoload.php';   // Poner siempre que usemos el composer

class devolverJmt{

    use \Firebase\JWT\JWT;  // requerimiento de la libreria 
    
    public static function ArmarJMT($nombre,$clave){
        $key = "miclave_secreta";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,               
            "nbf" => 1357000000,
            "nombre" => $nombre,
            "clave" => $clave
        );

        $jwt = JWT::encode($payload, $key);
        //echo $jwt;
        return $jwt;
        /*
        $headers = getallheaders(); //Leeo toda mi cabecera
        $miToken = $headers["mi_token"] ?? 'No Genero Token'; // Si se genero el Token aca lo obtengo de la cabecera
        
        try {
            return $decoded = JWT::decode($miToken, $key, array('HS256'));
            print_r($decoded); 
        } 
        catch (\Throwable $th) {
            echo $th->getMessage() . " Error JWT";
            return  $decoded = "Error JMT";
        }
        */
    }
}