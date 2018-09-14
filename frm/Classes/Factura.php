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
class Ftl_Factura extends Ftl_ClaseBase{
    //put your code here
    const TABLE             = 'F_FAC';


    //private $id = 0;
    public $TIPFAC;
    public $CODFAC;
    public $CODFACD;
    public $CODFACH;    
    public $FECFAC;
    public $CNOFAC;
    public $CNIFAC;
    public $TOTFAC;
    public $BAS1FAC;
	public $BAS4FAC;
    public $IIVA1FAC;
    public $BAS2FAC;
    public $IIVA2FAC;
    public $OB1FAC;
    public $OB2FAC;
    public $OB2FACORIG;	
	public $BNOFAC;
    public $ALICIVA;
    public $ALICIVA1;
    public $ALICIVA2;	
    public $DOCTIPO;


    public function getTipFac() {
        if(($this->TIPFAC == '51') or ($this->TIPFAC == '52') or ($this->TIPFAC == '53'))
            return '7';
        else
            return $this->TIPFAC;
    }
	
   public function getTipFacAfip() {
        $tipocbte =  $this->TIPFAC;
			if( $tipocbte == '1')
				$tipo_cbte = '1';
			if($tipocbte == '9')
				$tipo_cbte = '6';
			if($tipocbte == '2')
				$tipo_cbte = '3';
			if($tipocbte == '3')
				$tipo_cbte = '8';
			if($tipocbte == '5')
				$tipo_cbte = '2';
			if($tipocbte == '6')
				$tipo_cbte = '7';
		return $tipo_cbte;
    }

	public function setTipFac($tipfac) {
        $this->TIPFAC = $tipfac;
    }

  	public function getCodFac() {
        if($this->TIPFAC == '51')
            return $this->CODFAC + 10000;
        elseif ($this->TIPFAC == '53') 
            return $this->CODFAC + 80000;
        elseif ($this->TIPFAC == '52') 
            return $this->CODFAC + 90000;
        else
            return $this->CODFAC;            

    }  

	public function setCodFac($codfac) {
        $this->CODFAC = $codfac;
    }
  
	public function getFecFac() {
	     return $this->FECFAC;
    }

	public function setFecFac($fecfac) {
        $this->FECFAC = $fecfac;
    }
  
    public function getCnoFac() {
        return $this->CNOFAC;
    }

	public function setCnoFac($cnofac) {
        $this->CNOFAC = $cnofac;
    }
  
    public function getTotFac() {
        return $this->TOTFAC;
    }

	public function setTotFac($totfac) {
        $this->TOTFAC = $totfac;
    }
  
    public function getBas1Fac() {
        return $this->BAS1FAC;
    }

	public function setBas1Fac($bas1fac) {
        $this->BAS1FAC = $bas1fac;
    }

    public function getBas2Fac() {
        return $this->BAS2FAC;
    }

	public function setBas2Fac($bas2fac) {
        $this->BAS2FAC = $bas2fac;
    }

	public function setBas4Fac($bas4fac) {
        $this->BAS4FAC = $bas4fac;
    }
	
    public function getIiva1Fac() {
        return $this->IIVA1FAC;
    }

	public function setIiva1Fac($iiva1fac) {
        $this->IIVA1FAC = $iiva1fac;
    }

    public function getIiva2Fac() {
        return $this->IIVA2FAC;
    }

	public function setIiva2Fac($iiva2fac) {
        $this->IIVA2FAC = $iiva2fac;
    }
	
	
    public function getObs1Fac() {
        return $this->OB1FAC;
    }

	public function setObs1Fac($obs1fac) {
        $this->OB1FAC = (string) $obs1fac;
    }

    public function getObs2Fac() {
        return $this->OB2FAC;
    }
    
	public function getObs2FacOrig() {
        return $this->OB2FACORIG;
    }
	
	public function setObs2Fac($obs2fac) {
		$this->OB2FACORIG = $obs2fac;
		$this->OB2FAC = substr($obs2fac,6,2)."/".substr($obs2fac,4,2)."/".substr($obs2fac,0,4);
    }

	public function getBibFac() {
        return (string) $this->BNOFAC;
    }

	public function setBibFac($bibfac) {
        $this->BNOFAC = $bibfac;
    }

    public function  __construct($id=null,$guid=false,$tipfac,$codfac,$fecfac,$cnofac,$totfac,$bas1fac,$iiva1fac,$bas2fac,$bas4fac,$iiva2fac,$cnifac,$ificli)
    {
        parent::__construct();
		 $this->setTipFac($tipfac);
        $this->setCodFac($codfac);
        $this->setFecFac($fecfac);
        $this->setCnoFac($cnofac);
        $this->setTotFac($totfac);
        $this->setBas1Fac($bas1fac);
        $this->setIiva1Fac($iiva1fac);
        $this->setBas2Fac($bas2fac);
		$this->setBas4Fac($bas4fac);
        $this->setIiva2Fac($iiva2fac);		
    //    if(substr_count($cnifac, '-')==2)
		//echo "IFICLI";
		//var_dump($ificli);
		if(($ificli == '0' or $ificli == '1') and substr_count($cnifac, '-')==2)
        	$this->DOCTIPO = 80 ; //CUIT
        else
        	$this->DOCTIPO = 96; //DNI
        if($cnifac == '0')
		{
			$this->DOCTIPO = 99; //CONS FINAL
			$this->CNIFAC = 0;
		}
		else
			//$this->CNIFAC = eregi_replace("[a-zA-Z]","",str_replace("-","",str_replace(".","",$cnifac)));
			$this->CNIFAC = str_replace("-","",str_replace(".","",$cnifac));
        
        //var_dump($iiva1fac*100/$bas1fac);
        //var_dump(round($iiva1fac*100/$bas1fac,1));
        
		$this->ALICIVA = 3; // 0%
		$this->ALICIVA1 = 5; //21%
		$this->ALICIVA2 = 4; //10.5%
		
/*		
        if($iiva1fac == 0.0)
        	$this->ALICIVA = 3;
        else
        {
        	if(round($iiva1fac*100/$bas1fac,1)==10.5)
				$this->ALICIVA = 4;
			else	
				$this->ALICIVA = 5;			
        }
 */   
    }



    public static function obtenerListado ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden=null)
    {
        $campos = "*";
        $from   = DB_PREFIX . self::TABLE . " f" ;
        //return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
      	if($filtros)
        			return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
        else
          		return null;
    }

    
    public function _recuperar ( $tabla, $condicion, $campos="*" ){
    		parent::_recuperar ( $tabla, $condicion, $campos="*" );
    }
    
    public function guardar()
    {
        
        $res = null;
        self::$db = FTL_DB::getInstance();

/*        $datos = array (

            "TIPFAC"              => $this->getTipFac(),
            "CODFAC"              => $this->getCodFac(),
            "CNIFAC"			  => $this->CNIFAC,
            "FECFAC"              => $this->getFecFac(),
            "CNOFAC"              => $this->getCnoFac(),
            "TOTFAC"              => $this->getTotFac(),
            "BAS1FAC"             => $this->getBas1Fac(),
            "IIVA1FAC"            => $this->getIiva1Fac(), */
        $cod_barras = '';
		$cod_barras = C_CUIT . str_pad($this->getTipFacAfip(), 2, "0", STR_PAD_LEFT) . str_pad(C_PTOVTA,4,"0",STR_PAD_LEFT) . $this->getBibFac() . $this->getObs2FacOrig();
		//var_dump($cod_barras);
		$cod_barras .= $this->digv($cod_barras);
		//var_dump($cod_barras);
		$datos = array (			
            "OB1FAC"            => $cod_barras,
            "OB2FAC"            => $this->getObs2Fac(),
			"BNOFAC"			=> $this->getBibFac()
        );


        //$res = self::$db->update( DB_PREFIX.'F_FAC',$datos,'TIPFAC='.self::$db->escape($this->getTipFac()).' AND CODFAC='.self::$db->escape($this->getCodFac()) );      
        $res = self::$db->update( DB_PREFIX.'F_FAC',$datos,"TIPFAC='".$this->getTipFac()."' AND CODFAC=".$this->getCodFac());      
        self::$db->close();

        return $res;

    }

	 public function digv($nro)
    {
		$pares = 0;
		$impares = 0;
		for ($i = 1; $i <= strlen($nro); $i++) {
			// If I Mod 2 = 0 Then
			if ($i % 2 == 0) {
				// es par
				$pares += (int) substr($nro,$i-1,1);
				} else {
				// es impar
				$impares += (int) substr($nro,$i-1,1);
			}
		}
		//
		$impares = 3 * $impares;
		$total = $pares + $impares;
		$digito = 10 - ($total % 10);
		//
		if ($digito == 10) {
		$digito = 0;
		}
		return (string) $digito;
	}
}
?>
