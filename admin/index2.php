<?php
    require_once '../frm/init.php';
	require '../exceptionhandler.php';
	require '../wsaa.class.php';
	require '../wsfe.class.php';
	require 'config.php'; 

/**********************
			 * WSAA
			 * ********************/
			$wsaa = new WSAA('./'); 
			
			if($wsaa->get_expiration() < date("Y-m-d h:m:i")) {
			  if (!$wsaa->generar_TA()) {
			    	 array_push($errors,'Error al obtener el token auth de AFIP');
			  }
			} 


$wsfe = new WSFE('./');
 
 
// Carga el archivo TA.xml
if($wsfe->openTA())
	echo "WSFE open TA OK";
else
	echo "WSFE open TA Error";

//$wsfe->getTiposCbte();
//$wsfe->getTiposConcepto();
//$wsfe->getTiposIva();
//$wsfe->getTiposMonedas();
//$wsfe->getTiposTributos();
//$wsfe->getTiposDoc();

 
 
// devuelve el cae
$ptovta = 1; 
$tipocbte = 1;

$cmp = $wsfe->recuperaLastCMP($ptovta, $tipocbte);
//if($cmp == false) echo "erorrrrrrr cmppp";

echo "Ultimo comprobante: " . $cmp->FECompUltimoAutorizadoResult->CbteNro;
print_r($cmp);

print_r($cmp->FECompUltimoAutorizadoResult);
?>
