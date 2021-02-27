<?php

require 'exceptionhandler.php';
require 'wsaa.class.php';
require 'wsfe.class.php';
require 'admin/config.php';
require 'frm/init.php';
require('fpdf/fpdf.php');
/**********************
 * Ejemplo WSAA
 * ********************/

$wsaa = new WSAA('./'); 


if($wsaa->get_expiration() < date("Y-m-d h:m:i")) {
 // print("Genera TA");
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

$res = $wsfe->getCompConsultar($ptovta,$tipo_cbte,$numcbte);

$fecha = substr($res->FECompConsultarResult->ResultGet->CbteFch, 0, 4)."-".substr($res->FECompConsultarResult->ResultGet->CbteFch, 4, 2)."-".substr($res->FECompConsultarResult->ResultGet->CbteFch, 5, 2);
$arr = array('ver' => 1, 
			 'fecha' => $fecha, 
			 'cuit' => C_CUIT,
			 'ptoVta' => C_PTOVTA,
			 'tipoCmp' => $tipo_cbte,
			 'nroCmp' => $res->FECompConsultarResult->ResultGet->CbteDesde,
			 'importe' => $res->FECompConsultarResult->ResultGet->ImpTotal,
			 'moneda' => $res->FECompConsultarResult->ResultGet->MonId,
			 'ctz' => $res->FECompConsultarResult->ResultGet->MonCotiz,
//			 'tipoDocRec' => 80,
//			 'nroDocRec' => 20000000001,
			 'tipoCodAut' => "E",
			 'codAut' => $res->FECompConsultarResult->ResultGet->CodAutorizacion);

$str = json_encode($arr);

$userinput = "https://www.afip.gob.ar/fe/qr/?p=".base64_encode($str);
$urlqr = urlencode($userinput);
$image = 'https://chart.googleapis.com/chart?chs=160x160&cht=qr&chl='.$urlqr;


$pdf = new FPDF();

$pdf->AliasNbPages();
$pdf->AddPage();
// Insert a dynamic image from a URL
$pdf->Image($image,7,210,0,0,'PNG');
$pdf->Output();

//print"<img src=\"$image\" \/>";
	


?>