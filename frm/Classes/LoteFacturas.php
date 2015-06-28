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
				$res = parent::_obtenerListadoPaginado (  $from=DB_PREFIX . self::TABLE,$filtros=$condicion );
				$facs = array();
				var_dump($res);
				foreach($res as $row)
				{
						var_dump($row);
						$fac = new Ftl_Factura(null,false,$row['TIPFAC'],$row['CODFAC'],$row['FECFAC'],$row['CNOFAC'],$row['TOTFAC'],$row['BAS1FAC'],$row['IIVA1FAC']);
						array_push($facs,$fac);
				}
				return $facs;
	 }

	public function validarLote()
	{
				$consulta = "SELECT COUNT(1) FROM ".DB_PREFIX . self::TABLE. " WHERE TIPFAC = ".$this->TIPFAC." AND CODFAC < ".$this->CODFACD." AND OBS1FAC IS NULL";
				$res = parent::_getDatos($consulta);
				if($res[0] > 0)
					return false;
				else
					return true;
	}

	public function solicitarAfip()
	{
			$ws = new WSAfip();
			$ws->CallWSAA("WSFEv1");
			if ($ws->AuthOK())
			{
					$ws->prepareSolicitud($this->TIPFAC,$this->CODFACD,$this->CODFACH);
					$ws->FECAESolicitar();
					if($ws->solicitudOK())
					{
							$this->assignCAE($ws->getCAEs());
					}
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
