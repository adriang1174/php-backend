<?php
class Ftl_LoteFacturasTest extends PHPUnit_Framework_TestCase
{
    // ...

       public function setUp()
    {
        //$skip_session = true;
        //require "frm/init.php";
    }
    
    /*
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
	*/
    public function testSolicitarAfip()
    {
			// Create
		require "frm/init.php";	
        $a = new Ftl_LoteFacturas(1,60362,60365);
		$a->solicitarAfip();
        $this->assertEquals($a->facs[0]->OB1FAC,'123456789');
    }  
	// ...
}
