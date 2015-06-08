<?php

class Ftl_SessionBO {

    const SESSION_KEY       = SESSION_KEY;

    private $_usr            = null;
    private $_expirationTime = 3600; //seconds, 1 hour

    public function  __construct() {  }

    public function getUser ()
    {
        if ( $this->isLogged() )
        {
            if ( is_null($this->_usr) )
            {
                if ( isset ( $_SESSION[ self::SESSION_KEY ] ) )
                {
                    $this->_usr = unserialize($_SESSION[ self::SESSION_KEY ]);
                }
                else
                {
                    $this->_usr = unserialize( $_COOKIE[ self::SESSION_KEY ] );
                }
            }
            
        }
        return $this->_usr;
    }


    public function isLogged ()
    {
        return isset ( $_SESSION[ self::SESSION_KEY ] ) || (isset ( $_COOKIE[ self::SESSION_KEY ] ) && $_COOKIE[ self::SESSION_KEY ] != "");
    }



    public static function login ($user,$pass)
    {
        $oSession = new Ftl_SessionBO();
        $respuesta = Ftl_UsuarioBO::login(array(

            "tipo"  => Ftl_UsuarioBO::LOGIN_USR_CLAVE,
            "datos" => array ("usuario" => $user,"clave" => md5($pass))

        ));

        switch ( $respuesta->state )
        {
            case 0:
                $respuesta->message = "Los datos de acceso no corresponden a un usuario registrado.";
                break;
            case 1:
                $oSession->_usr = $respuesta->data;
                $oSession->saveLoggedUsr();
                $respuesta->data = $oSession;
                break;
        }

        return $respuesta;
    }





    private function saveLoggedUsr (  )
    {
        //primero guardo al objeto en session
        $_SESSION[self::SESSION_KEY] =  serialize( $this->_usr ) ;

        //despues lo guardo en cookie, por si el server no acepta session
        //setcookie(self::SESSION_KEY, serialize( $this->_usr ),time()+$this->_expirationTime);
        
    }

    public function logout ( $redirect = '' )
    {
        //Borro la session, si existe
        if ( isset( $_SESSION[self::SESSION_KEY] ) )
            unset ( $_SESSION[self::SESSION_KEY] );

        if ( isset( $_COOKIE[self::SESSION_KEY] ) ){
          
            $_COOKIE[ self::SESSION_KEY ] = null;
            setcookie( self::SESSION_KEY, '', time()+(-1*$this->_expirationTime) );
        
        }
            
        $this->_usr         = null;

        if ( $redirect )
            Ftl_Redirect::toPage ($redirect,true);

        return true;

    }
    

    

}
?>
