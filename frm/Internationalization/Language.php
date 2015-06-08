<?php

class Ftl_Language {

    const ES = 'ES';
    const EN = 'EN';
    const PT = 'PT';


    private static $_map = array (

        self::ES      => array('0','Español'),
        self::EN      => array('1','Inglés'),
        self::PT      => array('2','Portugués')

    );

    public static function getId ( $key = self::ES )
    {
        return ( self::$_map[ $key ][ 0 ] );
    }
    public static function getText ( $key = self::ES )
    {
        return ( self::$_map[ $key ][ 1 ] );
    }



}
?>
