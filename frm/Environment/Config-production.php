<?php

        define( 'SSL_ENABLED'               , true);
        define( 'HTTP_PROTOCOL'             , (SSL_ENABLED && Ftl_Path::isRunningUnderSSL() ? 'https:' : 'http:' ));
        define( 'URL_ROOT'                 , '//' . $_SERVER["HTTP_HOST"] .'/app/' );
        define( 'HTTP_URL_ROOT'             , HTTP_PROTOCOL . URL_ROOT );
        
        define( 'TS_OFFSET'                 , -3); //Hora Argentina
        
        //BASE DE DATOS
        define( 'DB_HOST'                   , 'localhost');
        define( 'DB_USER'                   , 'pablo15_amigo');
        define( 'DB_PASS'                   , 'id123!');
        define( 'DB_BASE'                   , 'pablo15_nokia_amigo');

        //FACEBOOK
        define( 'FB_APP_ID'                 , '1441426052808277');
        define( 'FB_APP_SECRET'             , '3121dd54f5101697c2b504bf9f45ee86');
        define( 'FB_APP_NAME'               , 'SelfieQueBuscaReyes');
        define( 'FB_APP_NAMESPACE'          , 'selfiequebuscareyes');
        define( 'FB_APP_URL'                , HTTP_PROTOCOL.'//apps.facebook.com/'.FB_APP_NAMESPACE);        
        
	
	//define( 'FB_PAGE_ID'                , '135593729949698');
        //define( 'FB_PAGE_URL'               , HTTP_PROTOCOL.'//www.facebook.com/iddevpage/');
	
	
        define( 'FB_PAGE_ID'                , '123576404337836');
        define( 'FB_PAGE_URL'               , HTTP_PROTOCOL.'//www.facebook.com/personalargentina/');
        
	
	define( 'FB_PAGE_URL_NOKIA'               , HTTP_PROTOCOL.'//www.facebook.com/nokiaargentina/');        
      
        define( 'FB_TAB_URL'                , FB_PAGE_URL.'app_'.FB_APP_ID);
        define( 'FB_TAB_URL_NOKIA'          , FB_PAGE_URL_NOKIA.'app_'.FB_APP_ID);
        define( 'FB_MOBILE_URL'             , HTTP_PROTOCOL.HTTP_URL_ROOT.'mobile.php');
        
        define( 'FB_SHARE_NAME'             , 'SelfieQueBuscaReyes');
        define( 'BY_APP_URL'                , 'http://bit.ly/selfiequebuscareyes');
        //SESSION
        define( 'SESSION_KEY'               , md5('prod_persnokia_admin') );


	        define( 'IP_KEY'                    , '87fce691cb0dad1ec5833e0e8ec160101d6dd400d797121b5e0bb16fe5952957');

        define( 'MAIL_CONTACTO'             ,'info@selfiequebuscareyes.com');
