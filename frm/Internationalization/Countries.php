<?php

class Ftl_Countries {

    const AR        = 'AR';
    const BR        = 'BR';
    const US        = 'US';

    private static $_map = array (

        self::AR        => array("Argentina","ES","-3"),
        self::BR        => array("Brasil","PT","-4"),
        self::US        => array("USA","EN","-8")

    );

    public static function getCountry ( $id = Ftl_Countries::AR )
    {
        return self::$_map[ $id ][ 0 ];
    }
    public static function getLanguage ( $id = Ftl_Countries::AR )
    {
        return self::$_map[ $id ][ 1 ];
    }
    public static function getTimeZone ( $id = Ftl_Countries::AR )
    {
        return self::$_map[ $id ][ 2 ];
    }

}
?>
