<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ObjectUtil
 *
 * @author Luki
 */
class Ftl_ObjectUtil {

    public static function toArray( $object )
    {

        if( !is_object( $object ) ) {
                return false;
        }

        $array = array();

        foreach( $object as $k => $v ) {

                if( is_object( $v ) || is_array( $v ) ) {
                        $array[ $k ] = self::toArray( $v );
                } else {
                        $array[ $k ] = $v;
                }

        }

        return $array;
    }

    public static function search( $obj, $needle ) {

            if( !is_object( $obj ) ) {
                    return false;
            }

            foreach( $obj as $k => $v ) {

                    if( $v == $needle ) {
                            return $k;
                    }

            }

            return false;

    }
}
?>
