<?php
class Ftl_LoteFacturasTest extends PHPUnit_Framework_TestCase
{
    // ...

       public function setUp()
    {
    }
    
    public function testCreateLote()
    {
        require "frm/init.php";
			// Create
        $a = new Ftl_LoteFacturas(1,60362,60365);

        $this->assertCount(4, $a->facs);
    }    

    public function testValidarLote()
    {
        require "frm/init.php";
			// Create
        $a = new Ftl_LoteFacturas(1,60362,60365);
        $this->assertTrue($a->validarLote());
    }    

	// ...
}
