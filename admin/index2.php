<?php
    require_once '../frm/init.php';
	require '../exceptionhandler.php';
	require '../wsaa.class.php';
	require '../wsfe.class.php';
	require 'config.php'; 


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

echo $cmp->FECompUltimoAutorizadoResult->CbteNro;
//print_r($cmp);

?>
