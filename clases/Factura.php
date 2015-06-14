<?php

class Class_Factura extends Ftl_Factura{

    public $token;
    public $real_uid;

    

    public function  __construct($id=null,$uid=false)
    {
        
			parent::__construct();      
        
    }

	public static function  obtenerListado($pagina=1,$reg_x_pagina=50,$filtros=null,$orden=null)
    {
        
			
        $filtros = str_replace ( 'CODFACD =' , 'CODFAC >=' , $filtros );
        $filtros = str_replace ( 'CODFACH =' , 'CODFAC <=' , $filtros );
        var_dump($filtros);
        return parent::obtenerListado($pagina,$reg_x_pagina,$filtros,$orden);      
        
    }  

}
?>
