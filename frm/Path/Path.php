<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Url
 *
 * @author Luki
 */
class Ftl_Path {
    
        public static function getFileName ( $url=null )
        {
            if ( !$url )
            {
                $script_name = explode( '/', $_SERVER[ 'SCRIPT_NAME' ] );
                return array_pop( $script_name );
            }
            $aux_url = explode( '/', $url );
            $uri = array_pop( $aux_url );

            $i=strpos($uri,"#");

            $uri = ($i !== false) ? substr($uri,0,$i) : $uri;

            $i=strpos($uri,"?");

            $uri = ($i !== false) ? substr($uri,0,$i) : $uri;

            return $uri;
        }


        

    /**
     * Retorna la url de un archivo.
     * Si $archivo es nulo toma por defecto la página actual.
     * 
     * @ssl:
     *  0: Retorna la url sin https
     *  1: Retorna la url con https
     *  2: Retorna la url por defecto, comprobando si viene o no con SSL

     * @author Luki
     */

        public static function getAbsoluteUrl($ssl=2,$file=null)
        {
                $page   = self::getFileName( $file );

                switch ($ssl)
                {
                    case 0: $server  = URL_ROOT ;break;
                    case 1: $server  = SSL_URL_ROOT ;break;
                    case 2: $server  = (self::isRunningUnderSSL() ? URL_ROOT : SSL_URL_ROOT);break;
                    
                }
                return $server . $page;
        }

        public static function getScriptName()
        {
            $script_name = explode( '/', $_SERVER[ 'SCRIPT_NAME' ] );
            return array_pop( $script_name );
            
        }

	/** Determine si actualmente esta corriendo en HTTPS */
	public static function isRunningUnderSSL()
	{
		$httpson=(isset($_SERVER["HTTPS"])?$_SERVER["HTTPS"]:"off");
		if(0==strcasecmp($httpson  ,"on"))
		{
			return(true);
		}
		$sslon=(isset($_SERVER["HTTP_SSL"])?$_SERVER["HTTP_SSL"]:"false");
		if(0==strcasecmp($sslon  ,"true"))
		{
			return(true);
		}
		return(false);
	}
}
?>
