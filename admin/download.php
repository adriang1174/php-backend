<?
require_once "../frm/init.php";

$id = $ioHelper->get('id');

$foto = new Class_Foto($id);


$file= PATH_UPLOADS.$foto->nombre; //file location
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.basename($file).'"');
header('Content-Length: ' . filesize($file));
readfile($file);
