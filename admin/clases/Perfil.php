<?php

class Perfil {

    const ADMIN = 1;
    const USUARIO = 2;

    private static $_map = array (

        self::ADMIN       => "Administrador",
        self::USUARIO     => "Usuario"
        
    );

    public static function getLista ()
    {
        return self::$_map;
    }

    public static function getNombre ( $id )
    {
        return self::$_map[ $id ];
    }
    

}
?>
