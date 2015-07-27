<?php

$cuit = '20177397203';                  # CUIT del emisor de las facturas
$cert = "keys/ghf.crt";                # The X.509 certificate in PEM format
$privatekey = "keys/ghf.key";          # The private key correspoding to CERT (PEM)
$wsfeurl = "https://wswhomo.afip.gov.ar/wsfev1/service.asmx"; // testing
$wsaaurl = "https://wsaahomo.afip.gov.ar/ws/services/LoginCms"; // testing
$dbname = "C:/work/factura_electronica/0012015.mdb";
define('DB', 'mysql');
//define('DB', 'msaccess');
?>