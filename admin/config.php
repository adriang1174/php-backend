<?php


define("C_CUIT",     "20177397203");
// Testing
//define("C_CERT",     "keys\ghf.crt");
//define("C_PRIVATEKEY",     "keys\ghf.key");
//define("C_WSFEURL",     "https://wswhomo.afip.gov.ar/wsfev1/service.asmx");
//define("C_WSAAURL",     "https://wsaahomo.afip.gov.ar/ws/services/LoginCms");

// Produccion
define("C_CERT",     "keys-prod/DBELLI_70b7dbedd376a070.crt");
define("C_PRIVATEKEY",     "keys-prod/privada");
define("C_WSAAURL",     "https://wsaa.afip.gov.ar/ws/services/LoginCms");
define("C_WSFEURL",     "https://servicios1.afip.gov.ar/wsfev1/service.asmx");

define("C_PTOVTA", 4);
$dbname = "Z:/Archivos de programa/FactuSol 2000/Datos/0012018.MDB";
//define ("DBNAME" ,"odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=C:\\Users\\Servidor\\Desktop\\0012018.MDB; Uid=; Pwd=;");
define ("DBNAME" ,"odbc:DRIVER={Microsoft Access Driver (*.mdb)}; DBQ=Z:\\Archivos de programa\\FactuSol 2000\\Datos\\0012018.MDB; Uid=; Pwd=;");
define('DB', 'msaccess');

?>