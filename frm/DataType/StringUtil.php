<?php

class Ftl_StringUtil {
    //put your code here

    public static function toArray($str)
    {
        return ((!is_array($str)) ? array( $str ) : $str );
    }

    public static function replaceVars( $str, $array, $options=array() )
    {
        if (is_null($str) || trim($str) == '')
        {
            return "";
        }

        $opts   =  array_merge
        (

                array
                (
                    "inTagStart"    => ':',
                    "inTagEnd"      => '',
                    "fnCallback"    => null
                )
                ,$options

        );
        

        $strRes = $str;

        foreach( $array as $key => $value ) {
                
                $strRes = str_replace( $opts['inTagStart'] . $key . $opts['inTagEnd'], ($opts['fnCallback'] ? $opts['fnCallback']($value) : $value), $strRes, $count );
        }

        return $strRes;

    }
    public static function tryStripSlashes( $str ) {
            return ( get_magic_quotes_gpc() ) ? stripslashes( $str ) : $str;
    }

    public static function startsWith($haystack,$needle,$case=true) {
        if($case){return (strcmp(substr($haystack, 0, strlen($needle)),$needle)===0);}
        return (strcasecmp(substr($haystack, 0, strlen($needle)),$needle)===0);
    }

    public static function endsWith($haystack,$needle,$case=true) {
        if($case){return (strcmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);}
        return (strcasecmp(substr($haystack, strlen($haystack) - strlen($needle)),$needle)===0);
    }


}
?>
