<?php

require_once '../frm/init.php';

$db = Ftl_ClaseBase::getDB();

$db->connect();
$usu = $db->fetchObject("select * from fb_registrados where uid = '251621738361659'");


if ($usu){
    
    $fb = new Ftl_FacebookUtil();
    $fb->setAccessToken($usu->token);
    $r = $fb->api("/{$usu->uid}/feed","post",array(
            'name'          => "Luki",
            'description'   => "desssss",
            'link'          => "www.ole.com.ar",
            'caption'       => "caption"
    ));
    
    var_dump($r);
}
$db->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        // put your code here
        ?>
    </body>
</html>
