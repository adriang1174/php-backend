<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of WSAfip
 *
 * @author Adrian Garcia
 */
class Ftl_WSAfip {
   


     public function  __construct()
    {
		define ("WSDL", "wsaa.wsdl");     # The WSDL corresponding to WSAA
		define ("CERT", "ghf.crt");       # The X.509 certificate in PEM format
		define ("PRIVATEKEY", "ghf.key"); # The private key correspoding to CERT (PEM)
		define ("PASSPHRASE", "xxxxx"); # The passphrase (if any) to sign
		define ("PROXY_HOST", "10.20.152.112"); # Proxy IP, to reach the Internet
		define ("PROXY_PORT", "80");            # Proxy TCP port
		define ("URL", "https://wsaahomo.afip.gov.ar/ws/services/LoginCms");
	
    }

public function CreateTRA($SERVICE)
{
  $TRA = new SimpleXMLElement(
    '<?xml version="1.0" encoding="UTF-8"?>' .
    '<loginTicketRequest version="1.0">'.
    '</loginTicketRequest>');
  $TRA->addChild('header');
  $TRA->header->addChild('uniqueId',date('U'));
  $TRA->header->addChild('generationTime',date('c',date('U')-60));
  $TRA->header->addChild('expirationTime',date('c',date('U')+60));
  $TRA->addChild('service',$SERVICE);
  $TRA->asXML('TRA.xml');
}
#==============================================================================
# This functions makes the PKCS#7 signature using TRA as input file, CERT and
# PRIVATEKEY to sign. Generates an intermediate file and finally trims the 
# MIME heading leaving the final CMS required by WSAA.
public function SignTRA()
{
  $STATUS=openssl_pkcs7_sign("TRA.xml", "TRA.tmp", "file://".CERT,
    array("file://".PRIVATEKEY, PASSPHRASE),
    array(),
    !PKCS7_DETACHED
    );
  if (!$STATUS) {exit("ERROR generating PKCS#7 signature\n");}
  $inf=fopen("TRA.tmp", "r");
  $i=0;
  $CMS="";
  while (!feof($inf)) 
    { 
      $buffer=fgets($inf);
      if ( $i++ >= 4 ) {$CMS.=$buffer;}
    }
  fclose($inf);
#  unlink("TRA.xml");
  unlink("TRA.tmp");
  return $CMS;
}
#==============================================================================
public function CallWSAA($CMS)
{
 /*
 $client=new SoapClient(WSDL, array(
          'proxy_host'     => PROXY_HOST,
          'proxy_port'     => PROXY_PORT,
          'soap_version'   => SOAP_1_2,
          'location'       => URL,
          'trace'          => 1,
          'exceptions'     => 0
          )); 
  $results=$client->loginCms(array('in0'=>$CMS));
  file_put_contents("request-loginCms.xml",$client->__getLastRequest());
  file_put_contents("response-loginCms.xml",$client->__getLastResponse());
  if (is_soap_fault($results)) 
    {exit("SOAP Fault: ".$results->faultcode."\n".$results->faultstring."\n");}
  return $results->loginCmsReturn;
  */
} 

public function AuthOK()
{
   return true;
} 

public function prepareSolicitud($tipfac,$codfacd,$codfach)
{
}

public function FECAESolicitar()
{
}

public function solicitudOK()
{
	return true;
}

public function getCAEs()
{
	$cae = array('CAE' => '123456789','FVTOCAE' => '2015-12-31');
	return $cae;
}

}
?>
