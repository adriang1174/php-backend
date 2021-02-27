<?php

require 'exceptionhandler.php';
require 'wsaa.class.php';
require 'wsfe.class.php';
require 'admin/config.php';
require 'frm/init.php';

function digv($nro)
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

/**********************
 * Ejemplo WSAA
 * ********************/

$wsaa = new WSAA('./'); 


if($wsaa->get_expiration() < date("Y-m-d h:m:i")) {
  $wsaa->generar_TA();
};



/**********************
 * Ejemplo WSFE
 * ********************
 */

$wsfe = new WSFE('./');
 
 
// Carga el archivo TA.xml
$wsfe->openTA();
	
 
// devuelve el cae
$ptovta = C_PTOVTA; 
$tipocbte = str_replace("'","",$_REQUEST['TIPFAC']);
$numcbte = $_REQUEST['id'];

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

			if ($tipocbte== '7')
			{
				if($numcbte > 100000 && $numcbte < 800000 )
				{
					$tipo_cbte = '51';
					$numcbte  -= 100000;
				}
				elseif( $row['CODFAC'] > 800000 &&  $row['CODFAC'] < 900000 )
				{
					$tipo_cbte = '53';
					$numcbte -= 800000;
				}
				else
				{
					$tipo_cbte = '52';
					$numcbte  -= 900000;
				}
			}	
			
//getCompConsultar($ptovta,$cbtetipo,$numcbte)
$res = $wsfe->getCompConsultar($ptovta,$tipo_cbte,$numcbte);

$cae = $res->FECompConsultarResult->ResultGet->CodAutorizacion;
$caefvto = $res->FECompConsultarResult->ResultGet->FchVto;
$cod_barras = C_CUIT . str_pad($tipo_cbte, 2, "0", STR_PAD_LEFT) . str_pad(C_PTOVTA,4,"0",STR_PAD_LEFT) . $cae . $caefvto;

//$cod_barras = C_CUIT . str_pad($this->getTipFac(), 2, "0", STR_PAD_LEFT) . str_pad(C_PTOVTA,4,"0",STR_PAD_LEFT) . $this->getBibFac() . $this->getObs2FacOrig();

$cod_barras .= digv($cod_barras);
	
//echo "CAE: ".$cae;
//echo "CAEFvto: ".$caefvto;
//echo "Cod Barras: ".$cod_barras;

$data = " CAE: ".$cae . ", CAEFvto: ".$caefvto. ", Cod Barras: ".$cod_barras;
//$resp["error"] = array("code" => 1,"msg"=>"Se debe especificar el estado.");

$resp   = array(
    "state" => 1,
    "data"  => $data,
    "error" => null
);

$fac = new Ftl_Factura(null,false,$tipocbte,$_REQUEST['id'],null,null,null,null,null,null,null,null,null);
$fac->setBibFac($cae);
$fac->setObs2Fac($caefvto);						
$fac->guardar();

//echo Ftl_JsonUtil::encode($resp);
echo json_encode($resp);


?>