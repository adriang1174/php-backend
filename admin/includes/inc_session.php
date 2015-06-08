<?php
require_once '../frm/init.php';
$oSession = new Ftl_SessionBO();
if (!$oSession->isLogged())
    Ftl_Redirect::toPage('login.php');
?>
