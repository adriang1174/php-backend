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
        
		//print_r($filtros);	
        $filtros = str_replace ( 'CODFACD =' , 'CODFAC >=' , $filtros );
        $filtros = str_replace ( 'CODFACH =' , 'CODFAC <=' , $filtros );
		$filtros = str_replace ( "''" , '@@' , $filtros );
		$filtros = str_replace ( "'" , '' , $filtros );
		$filtros = str_replace ( "@@" , "'" , $filtros );
		if (!empty($filtros))
			$filtros .= " AND  ((F.FECFAC = #25/06/2016# and F.HORFAC > #13:00:00#) or F.FECFAC > #25/06/2016# )";
		//$filtros .= " AND  ((F.FECFAC = #25/06/2016# and F.HORFAC > #13:00:00#) or F.FECFAC > #25/06/2016# )";
		//$filtros .= " AND CODFAC < 60000 ";
        //print_r($filtros);
		$res = parent::obtenerListado($pagina,$reg_x_pagina,$filtros,$orden);
		//Sumo bases imponibles y distintos IVA
		foreach ($res as &$fac)
		{
			$fac['BAS1FAC'] = $fac['BAS1FAC'] + $fac['BAS2FAC'];
			$fac['IIVA1FAC'] = $fac['IIVA1FAC'] + $fac['IIVA2FAC'];
		}
		//print_r($res);
        return   $res;     
        
    }  

}
?>
