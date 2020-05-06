<?php
//include_once 'datos.php';

class Usuario{
    
    public $email;
    public $clave;
   

    public function __construct($email,$clave){

        $this->email = $email;
        $this->clave = $clave;
        
    }
        
    
    public function BuscarUsuario($dato1,$dato2){
        
        $rta = "";
        if($dato1 == $this->clave && $dato2 == $this->email){
            $rta = $this->email . ' ' . $this->clave ;
        }
        return $rta;
    }

}