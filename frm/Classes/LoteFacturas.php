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
				$condicion =  " TIPFAC = ".$this->TIPFAC." AND CODFAC < ".$this->CODFACD." AND OBS1FAC IS NULL";
				$res = parent::_obtenerListadoPaginado (  $campos="*",$from=DB_PREFIX . self::TABLE,$pagina=1,$reg_x_pagina=50,$filtros=$condicion ,$orden=null);				
			
				if(count($res) > 0)
					return false;
				else
					return true;
	}

	public function solicitarAfip()
	{
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
			}
				
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
