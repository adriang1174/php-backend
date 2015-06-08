<?php

    require_once '../../frm/init.php';


    $usuario    = isset($_REQUEST['txtUsuario']) ? $_REQUEST['txtUsuario'] : "";
    $clave      = isset($_REQUEST['txtPass']) ? $_REQUEST['txtPass'] : "";
    $email      = isset($_REQUEST['txtEmail']) ? $_REQUEST['txtEmail'] : "";
    
    if ($usuario != "" && $clave != "")
    {
        
        $oUsr = Ftl_UsuarioBO::login(array(
            
            "tipo"  => Ftl_UsuarioBO::LOGIN_USR_CLAVE,
            "datos" => array ("usuario" => $usuario,"clave" => $clave)
            
        ));

        if ($oUsr)
        {
            $_SESSION['usuario'] = $oUsr;
            echo Ftl_JsonUtil::encode(array("estado" => 1));
        }
        else
        {
            echo Ftl_JsonUtil::encode(array("estado" => 0));
        }
        
    }
    else
        if ($email != "")
        {
            echo Ftl_JsonUtil::encode(array("estado" => 1));
        }
        else
            echo Ftl_JsonUtil::encode(array("estado" => -1));





?>