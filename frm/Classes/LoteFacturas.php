<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Factura
 *
 * @author Adrian Garcia
 */
//require_once '../frm/init.php';
class Ftl_LoteFacturas extends Ftl_ClaseBase{
    //put your code here
    const TABLE             = 'F_FAC';


    //private $id = 0;
     public $TIPFAC;
     public $CODFACD;
	 public $CODFACH;    

    public $facs;

    public function  __construct($tipfac,$codfacd,$codfach)
    {
        parent::__construct();
		  $this->setTipFac($tipfac);
        $this->setCodFacD($codfacd);
        $this->setCodFacH($codfach);
		  
			$this->facs = self::getFacturas();
			
    }

    public function getTipFac() {
        return $this->TIPFAC;
    }

	public function setTipFac($tipfac) {
        $this->TIPFAC = $tipfac;
    }

  	public function getCodFacD() {
        return $this->CODFACD;
    }  

	public function setCodFacD($codfac) {
        $this->CODFACD = $codfac;
    }

   public function getCodFacH() {
        return $this->CODFACH;
    }  

	public function setCodFacH($codfac) {
        $this->CODFACH = $codfac;
    }

   public function getFacturas()
	 {
				$condicion = " TIPFAC = ".$this->TIPFAC." AND CODFAC BETWEEN ".$this->CODFACD." AND ".$this->CODFACH;
				$res = parent::_obtenerListadoPaginado (  $campos="*",$from=DB_PREFIX . self::TABLE,$pagina=1,$reg_x_pagina=50,$filtros=$condicion ,$orden=null);
				$facs = array();
				foreach($res as $row)
				{
						$fac = new Ftl_Factura(null,false,$row['TIPFAC'],$row['CODFAC'],$row['FECFAC'],$row['CNOFAC'],$row['TOTFAC'],$row['BAS1FAC'],$row['IIVA1FAC']);
						array_push($facs,$fac);
				}
				return $facs;
	 }

	public function validarLote()
	{
				$condicion =  " TIPFAC = ".$this->TIPFAC." AND CODFAC < ".$this->CODFACD." AND OB1FAC IS NULL";
				$res = parent::_obtenerListadoPaginado (  $campos="*",$from=DB_PREFIX . self::TABLE,$pagina=1,$reg_x_pagina=50,$filtros=$condicion ,$orden=null);				
			
				if(count($res) > 0)
					return false;
				else
					return true;
	}

	public function solicitarAfip()
	{
			
			$errors = array();
			/**********************
			 * WSAA
			 * ********************/
			$wsaa = new WSAA('./'); 
			
			if($wsaa->get_expiration() < date("Y-m-d h:m:i")) {
			  if (!$wsaa->generar_TA()) {
			    	 array_push($errors,'error al obtener el TA');
			  }
			} 
			/**********************
			 * WSFE
			 * ********************
			 */
			$wsfe = new WSFE('./');
			// Carga el archivo TA.xml
			if(!$wsfe->openTA())
				array_push($errors,"WSFE open TA Error");
			
			foreach($this->facs as $regfac)
			{
				 $cbte = array(
	                     'Concepto' => 1,
	                     'DocTipo' => 80,
	                     'DocNro' => str_replace(".","",$regfac->CNIFAC),
		                 'CbteDesde' => $regfac->CODFACD,
	                     'CbteHasta' => $regfac->CODFACD,
	                     'CbteFch' => date('Ymd',strtotime($regfac->FECFAC)),
	                     'ImpTotal' => $regfac->TOTFAC,
	                     'ImpTotConc' => 0, //$regfac['ImpTotConc'],
	                     'ImpNeto' => $regfac->BAS1FAC,
	                     'ImpOpEx' => 0.0 ,//$regfac['ImpOpEx'],
	                     'ImpIVA' => $regfac->IIVA1FAC,
	                     'ImpTrib' => 0.0, //$regfac['ImpTrib'],
	                     'MonId' => 'PES',
	                     'MonCotiz' => 1,
	                     'Iva' => array( 'AlicIva' => array( 'Id' => 5, //21%
	                     									'BaseImp' => $regfac->BAS1FAC,
	                     									'Importe' => $regfac->IIVA1FAC
	                     									)
	                     				)
						 );
				  array_push($cbtes,$cbte);
			    }


			$cae = $wsfe->aut( count($cbtes), 1, 1, $cbtes);
			print_r($cae);
			/*
			$ws = new Ftl_WSAfip();
			$ws->CallWSAA("WSFEv1");
			if ($ws->AuthOK())
			{
					$ws->prepareSolicitud($this->TIPFAC,$this->CODFACD,$this->CODFACH);
					$ws->FECAESolicitar();
					if($ws->solicitudOK())
					{
							$this->assignCAE($ws->getCAEs());
							//$this->guardar();
					}
					//Manejar else de auth y solicitud
			}
			*/
				
	}
	
	public function assignCAE($caes)
	{
				foreach($this->facs as $fac)
				{
						$fac->setObs1Fac($caes['CAE']);
						$fac->setObs2Fac($caes['FVTOCAE']);						
				}
			
	}
	
    public function guardar()
    {
        
         foreach($this->facs as $fac)
			{
					$fac->guardar();
			}
	
    }

}
?>
