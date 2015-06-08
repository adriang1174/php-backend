<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IO
 *
 * @author Luki
 */
class Ftl_IOHelper {

    private $_data;

    public function  __construct()
    {
        $this->_data = array();
    }

    public function addFromArray ( $array = array() , $keys = '' )
    {
        if ( !is_array( $array ) )
        {
            return false;
        }

        if ( is_null( $keys ) || $keys == '' )
        {
            foreach ( $array as $k => $v )
            {
                
                $this->add( $k, $v );
            }
        }
        else
        {
            $keys = explode ( ',', $keys );

            foreach ( $keys as $k )
            {
                if (isset ( $array[ $k ] ) )
                {
                    $this->add( $k, $array[ $k ] );
                }

            }
        }
        return true;
    }
    

    public function add ( $key,$value )
    {

        if ( is_null( $key ) )
        {
            return false;
        }

        $this->_data [ $key ] = Ftl_StringUtil::tryStripSlashes( $value );

        return true;
        
    }

    public function getAll ()
    {
        return $this->_data;
    }


    public function get( $key , $defaultValue = '' )
    {
        
        return $this->output(isset ( $this->_data[ $key ] ) ? $this->_data[ $key ] : $defaultValue,false);
    }

    public function getEscaped( $key, $defaultValue = '', $applyNl2br = false, $charset = Ftl_CharsetEncoding::UTF8 )
    {
        return $this->output( isset ( $this->_data[ $key ] ) ? $this->_data[ $key ] : $defaultValue, true , $applyNl2br , $charset );
    }

    public function output ( $str, $escaped = true, $applyNl2br = false, $charset = Ftl_CharsetEncoding::UTF8 )
    {
       
        if ( !$escaped )
        {
            
            return $str;
        }

        if ( $applyNl2br )
        {
            return nl2br( htmlentities( $str, ENT_QUOTES, $charset) );
        }

        return htmlentities( $str, ENT_QUOTES, $charset);
        
    }

}
?>
