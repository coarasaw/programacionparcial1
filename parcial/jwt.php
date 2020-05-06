<?php
include 'jwtHeader.php';   

use \Firebase\JWT\JWT;  // requerimiento de la libreria

//Generamos el Payload-CargaDatos
$payload = array(
    "iss" => "http://example.org",
    "aud" => "http://example.com",
    "iat" => 1356999524,               //vencimiento del token
    "nbf" => 1357000000,
    "nombre" => "rosa",
    "clave" => "rosa"
);

//Generar el Token
 $jwt = JWT::encode($payload, $key);  

//Leo el toquen
echo $jwt; 

/* Token Generado lo guardo

/*
eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLm9yZyIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUuY29tIiwiaWF0IjoxMzU2OTk5NTI0LCJuYmYiOjEzNTcwMDAwMDAsIm5vbWJyZSI6InJvc2EiLCJjbGF2ZSI6InJvc2EifQ.nT18_hHKmdF5wprgz1a4oOoV5GFqIUPNG65_9GkJI0I
*/