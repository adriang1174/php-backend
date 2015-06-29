<?php
class Ftl_LoteFacturasTest extends PHPUnit_Framework_TestCase
{
    // ...

       public static function setUpBeforeClass()
    {
        //$skip_session = true;
        require "frm/init.php";
    }
    
    
    public function testCreateLote()
    {

			// Create
        $a = new Ftl_LoteFacturas(1,60362,60365);

        $this->assertCount(4, $a->facs);
    }    

    public function testValidarLote()
    {
			// Create
        $a = new Ftl_LoteFacturas(1,60362,60365);
        $this->assertTrue($a->validarLote());
    }    
	
    public function testSolicitarAfip()
    {
			// Create
        $a = new Ftl_LoteFacturas(1,60362,60365);
		$a->solicitarAfip();
        $this->assertEquals($a->facs[0]->OB1FAC,'123456789');
    }  
    
    public function testSolicitarAfipGuardar()
    {
			// Create
        $a = new Ftl_LoteFacturas(1,60362,60365);
		$a->solicitarAfip();
		$a->guardar();
		$f = new Ftl_Factura($id=null,$guid=false,1,60362,'','','','','');
		$f->_recuperar ( 'F_FAC', 'TIPFAC = 1 AND CODFAC = 60362', $campos="*" );
        $this->assertEquals($f->OB1FAC,'123456789');
    }  
	// ...
}
