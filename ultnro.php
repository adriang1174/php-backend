<?php
/*
En el change del select
	1- Llamada ajax para traer ult comprobante
	2- actualizar div ult comprobante
	test
	3- actualizar codfacd y codfach con ult comp + 1
	test
	4- submitir el form
*/
require 'admin/config.php'; 
require 'exceptionhandler.php';
require 'wsaa.class.php';
require 'wsfe.class.php';
require 'admin/config.php';
require 'frm/init.php';
/**********************
			 * WSAA
			 * ********************/
			$wsaa = new WSAA('./'); 
			
			if($wsaa->get_expiration() < date("Y-m-d H:i:s")) {
			  if (!$wsaa->generar_TA()) {
			    	 array_push($errors,'Error al obtener el token auth de AFIP');
			  }
			} 


$wsfe = new WSFE('./');
 
 
// Carga el archivo TA.xml
$wsfe->openTA();
/*
if($wsfe->openTA())
	echo "WSFE open TA OK";
else
	echo "WSFE open TA Error";
*/
//$wsfe->getTiposCbte();
//$wsfe->getTiposConcepto();
//$wsfe->getTiposIva();
//$wsfe->getTiposMonedas();
//$wsfe->getTiposTributos();
//$wsfe->getTiposDoc();

			$tipo_cbte = '';
			if($_GET['TIPFAC'] == '1')
				$tipo_cbte = '1';
			if($_GET['TIPFAC'] == '9')
				$tipo_cbte = '6';
			if($_GET['TIPFAC'] == '2')
				$tipo_cbte = '3';
			if($_GET['TIPFAC'] == '3')
				$tipo_cbte = '8';
			if($_GET['TIPFAC'] == '5')
				$tipo_cbte = '2';
			if($_GET['TIPFAC'] == '6')
				$tipo_cbte = '7'; 
			if($_GET['TIPFAC'] == '7')
				$tipo_cbte = '51'; 
			if($_GET['TIPFAC'] == '77')
				$tipo_cbte = '53'; 
			if($_GET['TIPFAC'] == '777')
				$tipo_cbte = '52'; 
// devuelve el cae
$ptovta = C_PTOVTA; 
//$tipocbte = 1;

$cmp = $wsfe->recuperaLastCMP($ptovta, $tipo_cbte);
//if($cmp == false) echo "erorrrrrrr cmppp";
//print_r($cmp);
//echo "Ultimo comprobante: " . $cmp->FECompUltimoAutorizadoResult->CbteNro;
if($tipo_cbte == '51')
{
	$codfacd = $cmp->FECompUltimoAutorizadoResult->CbteNro + 100001;
	$codfach = $cmp->FECompUltimoAutorizadoResult->CbteNro + 100001;	
}
elseif($tipo_cbte == '53')
{
	$codfacd = $cmp->FECompUltimoAutorizadoResult->CbteNro + 800001;
	$codfach = $cmp->FECompUltimoAutorizadoResult->CbteNro + 800001;	
}
elseif($tipo_cbte == '52')
{
	$codfacd = $cmp->FECompUltimoAutorizadoResult->CbteNro + 900001;
	$codfach = $cmp->FECompUltimoAutorizadoResult->CbteNro + 900001;	
}
else
{
$codfacd = $cmp->FECompUltimoAutorizadoResult->CbteNro + 1;
$codfach = $cmp->FECompUltimoAutorizadoResult->CbteNro + 1;
}
echo '{"ultnro":"'.$cmp->FECompUltimoAutorizadoResult->CbteNro.'","codfacd":"'.$codfacd.'","codfach":"'.$codfach.'"}';
//print_r($cmp);

//print_r($cmp->FECompUltimoAutorizadoResult);
?>
