<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DateTimeUtil
 *
 * @author Luki
 */
class Ftl_DateTimeUtil {

    private static $_config = array(
            'defaultDay'   => 'dd|dd',
            'defaultMonth' => 'mm|mmmm',
            'defaultYear'  => 'aaaa|yyyy',
            'formatDate'   => 'd/m/Y|Y-m-d',
            'formatTime'   => 'H:i:s|H:i:s'
    );

    private static $_longMonthsNames = array(
            '1'             => 'Enero|January',
            '2'             => 'Febrero|February',
            '3'             => 'Marzo|March',
            '4'             => 'Abril|April',
            '5'             => 'Mayo|May',
            '6'             => 'Junio|June',
            '7'             => 'Julio|July',
            '8'             => 'Agosto|August',
            '9'             => 'Septiembre|September',
            '10'            => 'Octubre|October',
            '11'            => 'Noviembre|November',
            '12'            => 'Diciembre|December'
    );

    private static $_shortMonthsNames = array(
            '1'             => 'Ene|Jan',
            '2'             => 'Feb|Feb',
            '3'             => 'Mar|Mar',
            '4'             => 'Abr|Apr',
            '5'             => 'May|May',
            '6'             => 'Jun|Jun',
            '7'             => 'Jul|Jul',
            '8'             => 'Ago|Aug',
            '9'             => 'Sep|Sep',
            '10'            => 'Oct|Oct',
            '11'            => 'Nov|Nov',
            '12'            => 'Dic|Dec'
    );

    public function  __construct( ) {

    }

    public static function dayToHtmlOptionList ( $selected = 0, $lang = null )
    {
        $i = 1;
        $lang = ( $lang ?  $lang :  LANG ) ;

        $option = "<option value=\"0\"".($i == $selected ? " selected=\"selected\"":"").">" . self::getConfig('defaultDay', $lang) . "</option>";

        
        for ( $i=1; $i<32; $i++ )
        {
            $option.= "<option value=\"$i\"". ($i == $selected ? " selected":"") . ">$i</option>\n";
        }

        return $option;

    }
    public static function monthToHtmlOptionList ( $selected = 0, $showMonthsNames = false, $lang = null )
    {
        $i = 1;
        $lang = ( $lang ?  $lang :  LANG ) ;
        
        $option = "<option value=\"0\"".($i == $selected ? " selected=\"selected\"":"").">" . self::getConfig('defaultMonth', $lang) . "</option>";

        for ( $i=1; $i<13; $i++ )
        {
            $option.= "<option value=\"$i\"". ($i == $selected ? " selected":"") . ">" . ($showMonthsNames ? self::showMonthName($i,false,$lang) : $i) . "</option>\n";
        }

        return $option;

    }
    public static function yearToHtmlOptionList ( $selected = 0, $lang = null )
    {
        $i = 1;
        $lang = ( $lang ?  $lang :  LANG ) ;
        
        $option = "<option value=\"0\"".($i == $selected ? " selected=\"selected\"":"").">" . self::getConfig('defaultYear', $lang) . "</option>";

        for ( $i=date('Y'); $i>1900; $i-- )
        {
            $option.= "<option value=\"$i\"". ($i == $selected ? " selected":"") . ">$i</option>\n";
        }

        return $option;

    }

    public static function showMonthName ($id, $long = true, $lang = null )
    {
        $lang = ( $lang ? Ftl_Language::getId( $lang ) : Ftl_Language::getId( LANG ) ) ;
        $names = null;
        if ( $lang )
        {
            if (array_key_exists( $id, self::$_longMonthsNames ))
            {
                $names = explode('|',self::$_longMonthsNames[$id]);
            }
        }
        else
        {
            if (array_key_exists( $id, self::$_shortMonthsNames ))
            {
                $names = explode('|',self::$_shortMonthsNames[$id]);
            }
        }

        if (is_array ($names) && isset($names[$lang]))
            return $names[$lang];
        else
            return "";

    }


    public static function gmtToLocal( $tz, $format=null )
    {
        //Si no defino formato, lo obtengo del idioma seteado por default.
        //Si no tengo configuracion para el idioma, muestro el formato en Ingles.
        if (!$format)
        {
            

            $format = Ftl_DateTimeUtil::getConfig( 'formatDate', LANG );

            if ( $format ) {
                
                $format.= ' ' . Ftl_DateTimeUtil::getConfig('formatTime', LANG);

            } else {
                
                $format = Ftl_DateTimeUtil::getConfig('formatDate', Ftl_Language::ES) . ' ' . Ftl_DateTimeUtil::getConfig('formatTime', Ftl_Language::ES);
                
            }

        }
        

        return gmdate( $format, time()+( 3600 * $tz ) );
    }

    public static function getGMT ( $format = null )
    {
        return self::gmtToLocal(0, $format);
    }

    private static function getConfig ($key,$lang = Ftl_Language::ES)
    {
        $lang = Ftl_Language::getId( $lang );
        $config = explode('|',self::$_config[$key]);
        return ( isset( $config[$lang] ) ? $config[$lang] : "");
    }

    public static function getFormatted ($dt,$format="Y-m-d")
    {
        return date( $format, strtotime( $dt ) );
    }

}
?>
