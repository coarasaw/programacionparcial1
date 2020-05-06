<?php

class Datos{
    public $archivo;
    
    public static function leer($archivo){
         
        $file = fopen($archivo,'r');
        $rta = fread($file,filesize($archivo));
        fclose($file);

        $datosDesearializadas = unserialize($rta);
        
        return $datosDesearializadas;
    }

    public static function guardarDatos($archivo,$datos){
        $file = fopen($archivo,'a');
        $rta = fwrite($file,serialize($datos));
        fclose($file);
        return $rta;
    }

    public static function guardarJSON($archivoJSON, $objeto)   {
        // LEEMOS
        $arrayJSON = Datos :: leerJSON($archivoJSON); // asi llamo una funcion estatica
        array_push($arrayJSON, $objeto); //Inserta uno o más elementos al final de un array
        // ESCRIBIMOS
        $file = fopen($archivoJSON, 'w');
        $rta = fwrite($file, json_encode($arrayJSON));
        fclose($file);
        // devolvemos el objeto json
        return $rta;
    }

    public static function leerJSON($archivoJSON) {
        $file = fopen($archivoJSON, 'r');
        $arrayString = fread($file, filesize($archivoJSON));
        $arrayJSON = json_decode($arrayString);
        fclose($file);
        // devolvemos el objeto json
        return $arrayJSON;
    }
}
