<?php
//include_once 'datos.php';

class Venta{
    
    public $id_producto;
    public $cantidad;
    public $nombreUsuario;
    public $tipo;

    public function __construct($id_producto,$cantidad,$nombreUsuario,$tipo){

        $this->id_producto = $id_producto;
        $this->cantidad = $cantidad;
        $this->nombreUsuario = $nombreUsuario;
        $this->tipo = $tipo;
       
    }
        
    
}