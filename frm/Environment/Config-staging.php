<?php

        define( 'SSL_ENABLED'               , true);
        define( 'HTTP_PROTOCOL'             , (SSL_ENABLED && Ftl_Path::isRunningUnderSSL() ? 'https:' : 'http:' ));
        define( 'URL_ROOT'                 , '//' . $_SERVER["HTTP_HOST"] .'/app/' );
        define( 'HTTP_URL_ROOT'             , HTTP_PROTOCOL . URL_ROOT );
        
        define( 'TS_OFFSET'                 , -3); //Hora Argentina
        
        //BASE DE DATOS
        define( 'DB_HOST'                   , 'localhost');
        define( 'DB_USER'                   , 'root');
        define( 'DB_PASS'                   , '');
        define( 'DB_BASE'                   , 'gd2_personal_nokia');

        //FACEBOOK
        define( 'FB_APP_ID'                 , '1441426052808277');
        define( 'FB_APP_SECRET'             , '3121dd54f5101697c2b504bf9f45ee86');
        define( 'FB_APP_NAME'               , 'SelfieQueBuscaSelfie');
        define( 'FB_APP_NAMESPACE'          , 'selfiequebuscaselfie');
        define( 'FB_APP_URL'                , HTTP_PROTOCOL.'//apps.facebook.com/'.FB_APP_NAMESPACE);        
        
        define( 'FB_PAGE_ID'                , '135593729949698');
        define( 'FB_PAGE_URL'               , HTTP_PROTOCOL.'//www.facebook.com/iddevpage/');
        
        define( 'FB_TAB_URL'                , FB_PAGE_URL.'app_'.FB_APP_ID);
        define( 'FB_MOBILE_URL'             , HTTP_PROTOCOL.HTTP_URL_ROOT.'mobile.php');
        
        define( 'FB_SHARE_NAME'             , 'SelfieQueBuscaSelfie');
        
        //SESSION
        define( 'SESSION_KEY'               , md5('prod_persnokia_admin') );
?>
