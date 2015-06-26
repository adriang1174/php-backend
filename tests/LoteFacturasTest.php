<?php
class LoteFacturasTest extends PHPUnit_Framework_TestCase
{
    // ...

       public function setUp()
    {
    }
    
    public function testCreateLote()
    {
        require "../frm/init.php";
			// Create
        $a = new LoteFacturas(1,60362,60365);

        $this->assertCount(4, $a->facs);
    }    


	// ...
}
