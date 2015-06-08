<?php
class Ftl_Environment
{
    // <editor-fold defaultstate="collapsed" desc="Ftl_Environment::CONSTANTS">
    const LOCAL         = 'LOCAL';
    const DEVELOPMENT   = 'DEVELOPMENT';
    const STAGING       = 'STAGING';
    const PRODUCTION    = 'PRODUCTION';
    public static $servers    = array
        (
            self::LOCAL        => array
            (
                "localhost",
                "localhost:8080",
                "127.0.0.1:8080",
                "127.0.0.1"
            ),
            self::DEVELOPMENT   => array   ("104.131.83.197","104.131.83.197/admin/","104.131.83.197:8080"),
            self::STAGING      => array    ("staging.identidad-digital.com.ar"),
            self::PRODUCTION   => array    ("selfiequebuscareyes.com","www.selfiequebuscareyes.com")

        );
    // </editor-fold>

    
    public static function exists($server_name_or_addr){
        return in_array(strtolower($server_name_or_addr), self::$servers[self::detect()]);
    }
    public static function detect()
    {
        $host       = $_SERVER['HTTP_HOST'];


        if (in_array($host, self::$servers[self::PRODUCTION]))
        {
                return self::PRODUCTION;
        }
        else if (in_array($host, self::$servers[self::STAGING]))
        {
                return self::STAGING;
        }
        else if (in_array($host, self::$servers[self::DEVELOPMENT]))
        {
                return self::DEVELOPMENT;
        }
        else if (in_array($host, self::$servers[self::LOCAL]))
        {
                return self::LOCAL;
        }
        else
                return -1;

    }    
}

?>
