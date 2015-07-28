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
						$fac = new Ftl_Factura(null,false,$row['TIPFAC'],$row['CODFAC'],$row['FECFAC'],$row['CNOFAC'],$row['TOTFAC'],$row['BAS1FAC'],$row['IIVA1FAC'],$row['CNIFAC']);
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
			$cbtes = array();
			/**********************
			 * WSAA
			 * ********************/
			$wsaa = new WSAA('./'); 
			
			if($wsaa->get_expiration() < date("Y-m-d h:m:i")) {
			  if (!$wsaa->generar_TA()) {
			    	 array_push($errors,'Error al obtener el token auth de AFIP');
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
	                     'DocNro' => str_replace("-","",str_replace(".","",$regfac->CNIFAC)),
		                 'CbteDesde' => $regfac->CODFAC,
	                     'CbteHasta' => $regfac->CODFAC,
	                     //'CbteFch' => date('Ymd',strtotime($regfac->FECFAC)),
	                     'CbteFch' => date('Ymd'),
	                     'ImpTotal' => str_replace("$","",$regfac->TOTFAC),
	                     'ImpTotConc' => 0, //$regfac['ImpTotConc'],
	                     'ImpNeto' => str_replace("$","",$regfac->BAS1FAC),
	                     'ImpOpEx' => 0.0 ,//$regfac['ImpOpEx'],
	                     'ImpIVA' => str_replace("$","",$regfac->IIVA1FAC),
	                     'ImpTrib' => 0.0, //$regfac['ImpTrib'],
	                     'MonId' => 'PES',
	                     'MonCotiz' => 1,
	                     'Iva' => array( 'AlicIva' => array( 'Id' => 5, //21%
	                     									'BaseImp' => str_replace("$","",$regfac->BAS1FAC),
	                     									'Importe' => str_replace("$","",$regfac->IIVA1FAC)
	                     									)
	                     				)
						 );
				  array_push($cbtes,$cbte);
			    }

			//print_r($cbtes);
			$result = $wsfe->aut( count($cbtes), 1, 1, $cbtes);
			print_r($result);
			//Chequeo de Errores 
			if(empty($result->FECAESolicitarResult->FeDetResp->FECAEDetResponse->Resultado))
				  array_push($errors, "Han ocurrido errores al autorizar el comprobante en AFIP: ".print_r($result,true));
			else
				 if($result->FECAESolicitarResult->FeDetResp->FECAEDetResponse->Resultado != 'A')
				 	 array_push($errors, "Han ocurrido errores al autorizar el comprobante en AFIP: ".print_r($result,true));
				 else
					 //Asigna el CAE a las Facturas
					 $this->assignCAE($result->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CAE,$result->FECAESolicitarResult->FeDetResp->FECAEDetResponse->CAEFchVto);
			
			
			//print_r($this->facs);
			return $errors;
				
	}
	
	public function assignCAE($cae,$caefvto)
	{
				foreach($this->facs as $fac)
				{
						$fac->setObs1Fac($cae);
						$fac->setObs2Fac($caefvto);						
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
