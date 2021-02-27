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
//				$condicion = " TIPFAC = ".$this->TIPFAC." AND CODFAC BETWEEN ".$this->CODFACD." AND ".$this->CODFACH;
//				$res = parent::_obtenerListadoPaginado (  $campos="*",$from=DB_PREFIX . self::TABLE,$pagina=1,$reg_x_pagina=50,$filtros=$condicion ,$orden=null);

	 			//Aqui ajuste de condicion para manejar documentos M
	 			// TIPFAC, CODFACD,CODFACH
	 			if( $this->TIPFAC == "'7'") 
	 			{
	 				$tipfac = $this->TIPFAC;
	 				//$codfacd = $this->CODFACD + 100000;
	 				//$codfach = $this->CODFACH + 100000;
	 			}
	 			elseif( $this->TIPFAC == "'77'") 
	 			{
	 				$tipfac = "'7'";
	 				//$codfacd = $this->CODFACD + 800000;
	 				//$codfach = $this->CODFACH + 800000;	 				
	 			}
	 			elseif ($this->TIPFAC == "'777'") {
	 				$tipfac = "'7'";
	 				//$codfacd = $this->CODFACD + 900000;
	 				//$codfach = $this->CODFACH + 900000;
	 			}
	 			else
	 			{
	 				$tipfac = $this->TIPFAC;
		
	 			}
	 			$codfacd = $this->CODFACD ;
	 			$codfach = $this->CODFACH ;	 

				$condicion = " F.CLIFAC = C.CODCLI AND F.TIPFAC = ".$tipfac." AND F.CODFAC BETWEEN ".$codfacd." AND ".$codfach." AND  F.FECFAC >= #25/06/2016#";
				//var_dump($condicion);
				$res = parent::_obtenerListadoPaginado (  $campos="F.TIPFAC,F.CODFAC,F.FECFAC,F.CNOFAC,F.TOTFAC,F.BAS1FAC,F.IIVA1FAC,F.CNIFAC,C.IFICLI,F.BAS2FAC,F.IIVA2FAC,F.BAS4FAC",$from=DB_PREFIX . self::TABLE." F, F_CLI C",$pagina=1,$reg_x_pagina=50,$filtros=$condicion ,$orden=null);
				$facs = array();

				foreach($res as $row)
				{
						// Aqui hacemos la alteracion para manejar NCM y NDM 
						// FM => TIPFAC = 7, CODFAC > 10000  => TIPFAC = 7, CODFAC = CODFAC - 10000
						// NCM => TIPFAC = 7, CODFAC > 80000  => TIPFAC = 77, CODFAC = CODFAC - 80000
						// NDM => TIPFAC = 7, CODFAC > 90000 y w => TIPFAC = 777, CODFAC = CODFAC - 90000
						//var_dump($row['CODFAC']);
						if ($row['TIPFAC']== '7')
						{
							$row['CODFAC']+=0;
							if($row['CODFAC'] > 100000 && $row['CODFAC'] < 800000 )
							{
								$tipfac = '51';
								$codfac = $row['CODFAC']  - 100000;
							}
							elseif( $row['CODFAC'] > 800000 &&  $row['CODFAC'] < 900000 )
							{
								$tipfac = '53';
								$codfac = $row['CODFAC']  - 800000;
							}
							else
							{
								$tipfac = '52';
								$codfac = $row['CODFAC']  - 900000;
							}
							$row['CODFAC'] = $codfac;
							$row['TIPFAC'] = $tipfac;

						}											
						$fac = new Ftl_Factura(null,false,$row['TIPFAC'],$row['CODFAC'],$row['FECFAC'],$row['CNOFAC'],$row['TOTFAC'],$row['BAS1FAC'],$row['IIVA1FAC'],$row['BAS2FAC'],$row['BAS4FAC'],$row['IIVA2FAC'],$row['CNIFAC'],$row['IFICLI']);
						array_push($facs,$fac);
				}
				//var_dump($facs);
				return $facs;
	 }

	public function validarLote()
	{
			//	$condicion =  " TIPFAC = ".$this->TIPFAC." AND CODFAC = ".$this->CODFACD."-1  AND OB1FAC IS NULL";
			//	$res = parent::_obtenerListadoPaginado (  $campos="*",$from=DB_PREFIX . self::TABLE,$pagina=1,$reg_x_pagina=50,$filtros=$condicion ,$orden=null);				
			
			//	if(count($res) > 0)
			//		return false;
			//	else
					return true;
	}

	public function getLastComp()
	{
			$errors = array();
			$cbtes = array();
			/**********************
			 * WSAA
			 * ********************/
			$wsaa = new WSAA('./'); 
			
			if($wsaa->get_expiration() < date("Y-m-d H:i:s")) {
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
	
			$ptovta = C_PTOVTA; 
			//$tipocbte = $this->getTipFac();
			$tipo_cbte = '';
			if($this->TIPFAC == "'1'")
				$tipo_cbte = '1';
			if($this->TIPFAC == "'9'")
				$tipo_cbte = '6';
			if($this->TIPFAC == "'2'")
				$tipo_cbte = '3';
			if($this->TIPFAC == "'3'")
				$tipo_cbte = '8';
			if($this->TIPFAC == "'5'")
				$tipo_cbte = '2';
			if($this->TIPFAC == "'6'")
				$tipo_cbte = '7';
			if($this->TIPFAC == "'7'")
				$tipo_cbte = '51';
			if($this->TIPFAC == "'77'")
				$tipo_cbte = '53';
			if($this->TIPFAC == "'777'")
				$tipo_cbte = '52';

			//var_dump($this->TIPFAC);
			//var_dump($tipo_cbte);
			$cmp = $wsfe->recuperaLastCMP($ptovta, $tipo_cbte);
			//print_r($cmp);
			if($cmp == false) 
				return "Error retornando Ult. Nro.";
			else
				return $cmp->FECompUltimoAutorizadoResult->CbteNro;	
	}
	
	public function solicitarAfip()
	{
			
			$errors = array();
			$cbtes = array();
			/**********************
			 * WSAA
			 * ********************/
			$wsaa = new WSAA('./'); 
			
			if($wsaa->get_expiration() < date("Y-m-d H:i:s")) {
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
			
			//print_r($this->facs);
			foreach($this->facs as $regfac)
			{
				if(str_replace(" ","",str_replace("-","",str_replace(".","",$regfac->CNIFAC))) == '') //Doc en blanco
				{
						array_push($errors,"El cliente no tiene cargado el nro de documento para el comprobante ".$regfac->CODFAC);
						return $errors;
				}
				 $totiva = abs(str_replace("$","",$regfac->IIVA1FAC)) + abs(str_replace("$","",$regfac->IIVA2FAC));
				 $iva1 = abs(str_replace("$","",$regfac->IIVA1FAC));
				 $iva2 = abs(str_replace("$","",$regfac->IIVA2FAC));
				 if ($totiva == 0.0)
						// Iva 0%
						$aliciva = array( 'Id' => $regfac->ALICIVA , //3 0%
	                     									'BaseImp' => abs(str_replace("$","",$regfac->BAS1FAC)),
	                     									'Importe' => abs(str_replace("$","",$regfac->IIVA1FAC))
	                     									);
				 elseif ($iva2 == 0.0 and $iva1 > 0.0)
						//solo IVA 21%
						$aliciva = array( 'Id' => $regfac->ALICIVA1 , //5 .21%
	                     									'BaseImp' => abs(str_replace("$","",$regfac->BAS1FAC)),
	                     									'Importe' => abs(str_replace("$","",$regfac->IIVA1FAC))
	                     									);
				elseif  ($iva2 > 0.0 and $iva1 == 0.0)
						//solo IVA 10.5%
						$aliciva = array( 'Id' => $regfac->ALICIVA2 , //4 10.5%
	                     									'BaseImp' => abs(str_replace("$","",$regfac->BAS2FAC)),
	                     									'Importe' => abs(str_replace("$","",$regfac->IIVA2FAC))
	                     									);				
				elseif  ($iva2 > 0.0 and $iva1 > 0.0)
						// IVA 10.5% e IVA 21%
						$aliciva = array(
											array( 'Id' => $regfac->ALICIVA1 , //5 .21%
	                     									'BaseImp' => abs(str_replace("$","",$regfac->BAS1FAC)),
	                     									'Importe' => abs(str_replace("$","",$regfac->IIVA1FAC))
	                     									),
											array( 'Id' => $regfac->ALICIVA2 , //4 10.5%
	                     									'BaseImp' => abs(str_replace("$","",$regfac->BAS2FAC)),
	                     									'Importe' => abs(str_replace("$","",$regfac->IIVA2FAC))
	                     									)
										);									
				 $cbte = array(
	                     'Concepto' => 1,
	                     //'DocTipo' => 80,
	                     'DocTipo' => $regfac->DOCTIPO,
	                     'DocNro' => str_replace("-","",str_replace(".","",$regfac->CNIFAC)),
		                 'CbteDesde' => $regfac->CODFAC,
	                     'CbteHasta' => $regfac->CODFAC,
	                     'CbteFch' => date('Ymd',strtotime($regfac->FECFAC)),
	                     //'CbteFch' => date('Ymd'),
	                     'ImpTotal' => abs(str_replace("$","",$regfac->TOTFAC)),
	                     'ImpTotConc' => 0, //$regfac['ImpTotConc'],
						 //Agregar suma de bas1fac + bas2fac
	                     'ImpNeto' => abs(str_replace("$","",$regfac->BAS1FAC)) + abs(str_replace("$","",$regfac->BAS2FAC)) ,
	                     'ImpOpEx' => abs(str_replace("$","",$regfac->BAS4FAC)), //0.0 ,
						 //Agregar suma de iva1fac + iva2fac
	                     'ImpIVA' => abs(str_replace("$","",$regfac->IIVA1FAC)) + abs(str_replace("$","",$regfac->IIVA2FAC)),
	                     'ImpTrib' => 0.0, //$regfac['ImpTrib'],
	                     'MonId' => 'PES',
	                     'MonCotiz' => 1,
						 //Cargar array de IVA
						 /*
	                     'Iva' => array( 'AlicIva' => array( 'Id' => $regfac->ALICIVA , //5 .21%
	                     									'BaseImp' => abs(str_replace("$","",$regfac->BAS1FAC)),
	                     									'Importe' => abs(str_replace("$","",$regfac->IIVA1FAC))
	                     									)
	                     				)
						*/
						'Iva' => array( 'AlicIva' => $aliciva
	                     				)
						 );
				  array_push($cbtes,$cbte);
			    }

			//print_r($cbtes);
			if($this->TIPFAC == "'1'")
				$tipo_cbte = '1';
			if($this->TIPFAC == "'9'")
				$tipo_cbte = '6';
			if($this->TIPFAC == "'2'")
				$tipo_cbte = '3';
			if($this->TIPFAC == "'3'")
				$tipo_cbte = '8';
			if($this->TIPFAC == "'5'")
				$tipo_cbte = '2';
			if($this->TIPFAC == "'6'")
				$tipo_cbte = '7';
			if($this->TIPFAC == "'7'")
				$tipo_cbte = '51';
			if($this->TIPFAC == "'77'")
				$tipo_cbte = '53';
			if($this->TIPFAC == "'777'")
				$tipo_cbte = '52';

			$result = $wsfe->aut( count($cbtes), $tipo_cbte, C_PTOVTA, $cbtes);
			//print_r($result);
			//Chequeo de Errores 
			//Mejorar chequeo para multiples DetReponse
			//FeCabResp->Resultado A o P, entonces mandar Arrar FECAEDetREsponse a assign CAE
			if(empty($result->FECAESolicitarResult->FeCabResp->Resultado))
				  array_push($errors, "Han ocurrido errores al autorizar el comprobante en AFIP: ".print_r($result,true));
			else
				 if($result->FECAESolicitarResult->FeCabResp->Resultado != 'A' and $result->FECAESolicitarResult->FeCabResp->Resultado != 'P')
				 { 	 array_push($errors, "Han ocurrido errores al autorizar el comprobante en AFIP: ".str_replace("\n",'',preg_replace('/[^A-Za-z0-9\ -]/', '',print_r($result->FECAESolicitarResult->FeDetResp->FECAEDetResponse->Observaciones,true))));
				 	 array_push($errors, $result->FECAESolicitarResult->Errors->Err->Msg);
					 //print_r($result,true);
				 }
				 else
					 //Asigna el CAE a las Facturas
					 $this->assignCAE($result->FECAESolicitarResult->FeDetResp->FECAEDetResponse);
			
			
			//print_r($this->facs);
			//print_r($errors);
			return $errors;
				
	}
	
	public function assignCAE($fecaedetresponse)
	{
				
				foreach($this->facs as &$fac)
				{
						if(is_array($fecaedetresponse))
						{
							foreach($fecaedetresponse as $r)
							{
									if($r->CbteDesde == $fac->CODFAC and $r->Resultado == 'A')
									{
										$cae = $r->CAE;
										$caefvto = $r->CAEFchVto; 	
									}
							}
						}
						else
						{
							$cae = $fecaedetresponse->CAE;
							$caefvto = $fecaedetresponse->CAEFchVto;
						}
						
						$fac->setBibFac($cae);
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
