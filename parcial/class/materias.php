<?php

include_once 'datos.php';

class Marteras{

    public $nombre;
    public $cuatrimestre;
   
    
    public function __construct($id,$nombre,$cuatrimestre){
        $this->id = $id ;
        $this->nombre = $nombre;
        $this->cuatrimestre = $cuatrimestre;
        
    }

    public function saveMateria($archivoJSON){
        return Datos::guardarJSON($archivoJSON, $this);
    }
        
}