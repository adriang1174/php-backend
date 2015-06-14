<?php

class Class_Factura extends Ftl_Factura{

    public $token;
    public $real_uid;

    

    public function  __construct($id=null,$uid=false)
    {
        
			parent::__construct();      
        
    }

	public static function  obtenerListado()
    {
        
			parent::obtenerListado();      
        
    }  

}
?>
