<?php

class Class_Factura extends Ftl_Factura{

    public $token;
    public $real_uid;

    

    public function  __construct($id=null,$guid=false,$tipfac=null,$codfac=null,$fecfac=null,$cnofac=null,$totfac=null,$bas1fac=null,$iiva1fac=null)
    {
        
			parent::__construct($id=null,$guid=false,$tipfac,$codfac,$fecfac,$cnofac,$totfac,$bas1fac,$iiva1fac);      
        
    }

	public static function  obtenerListado($pagina=1,$reg_x_pagina=50,$filtros=null,$orden=null)
    {
        
			
        $filtros = str_replace ( 'CODFACD =' , 'CODFAC >=' , $filtros );
        $filtros = str_replace ( 'CODFACH =' , 'CODFAC <=' , $filtros );
        //var_dump($filtros);
        return parent::obtenerListado($pagina,$reg_x_pagina,$filtros,$orden);      
        
    }  

}
?>
