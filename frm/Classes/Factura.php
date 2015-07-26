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
    public $CNIFAC;
    public $CODFACH;    
    public $FECFAC;
    public $CNOFAC;
    public $CNIFAC;
    public $TOTFAC;
    public $BAS1FAC;
    public $IIVA1FAC;
    public $OB1FAC;
    public $OB2FAC;


    public function getTipFac() {
        return $this->TIPFAC;
    }

	public function setTipFac($tipfac) {
        $this->TIPFAC = $tipfac;
    }

  	public function getCodFac() {
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
  
    public function getIiva1Fac() {
        return $this->IIVA1FAC;
    }

	public function setIiva1Fac($iiva1fac) {
        $this->IIVA1FAC = $iiva1fac;
    }

    public function getObs1Fac() {
        return $this->OB1FAC;
    }

	public function setObs1Fac($obs1fac) {
        $this->OB1FAC = $obs1fac;
    }

    public function getObs2Fac() {
        return $this->OB2FAC;
    }

	public function setObs2Fac($obs2fac) {
        $this->OB2FAC = $obs2fac;
    }


    public function  __construct($id=null,$guid=false,$tipfac,$codfac,$fecfac,$cnofac,$totfac,$bas1fac,$iiva1fac)
    {
        parent::__construct();
		 $this->setTipFac($tipfac);
        $this->setCodFac($codfac);
        $this->setFecFac($fecfac);
        $this->setCnoFac($cnofac);
        $this->setTotFac($totfac);
        $this->setBas1Fac($bas1fac);
        $this->setIiva1Fac($iiva1fac);
    }



    public static function obtenerListado ($pagina=1,$reg_x_pagina=50,$filtros=null,$orden=null)
    {
        $campos = "*";
        $from   = DB_PREFIX . self::TABLE . " f" ;
        return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
      	//if($filtros)
        	//		return parent::_obtenerListadoPaginado($campos, $from, $pagina, $reg_x_pagina, $filtros, $orden);
        //else
         // 		return null;
    }

    
    public function _recuperar ( $tabla, $condicion, $campos="*" ){
    		parent::_recuperar ( $tabla, $condicion, $campos="*" );
    }
    
    public function guardar()
    {
        
        $res = null;
        self::$db = FTL_DB::getInstance();

        $datos = array (

            "TIPFAC"              => $this->getTipFac(),
            "CODFAC"              => $this->getCodFac(),
            "CNIFAC"			  => $this->CNIFAC;
            "FECFAC"              => $this->getFecFac(),
            "CNOFAC"              => $this->getCnoFac(),
            "TOTFAC"              => $this->getTotFac(),
            "BAS1FAC"             => $this->getBas1Fac(),
            "IIVA1FAC"            => $this->getIiva1Fac(),
            "OB1FAC"            => $this->getObs1Fac(),
            "OB2FAC"            => $this->getObs2Fac()
        );


        $res = self::$db->update( DB_PREFIX.'F_FAC',$datos,'TIPFAC='.self::$db->escape($this->getTipFac()).' AND CODFAC='.self::$db->escape($this->getCodFac()) );      
        self::$db->close();

        return $res;

    }

}
?>
