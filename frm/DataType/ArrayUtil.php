<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Array
 *
 * @author Luki
 */
class Ftl_ArrayUtil {
    //put your code here

        public static function toString ( array $array )
        {

            

        }

        public static function toObject( array $array )
        {
		$obj = new stdClass();

		foreach( $array as $k => $v ) {

			if( is_array( $v ) || is_object( $v ) ) {
				$obj->$k = self::toObject( $v );
			} else {
				$obj->$k = $v;
			}

		}

		return $obj;
        }

        public static function comparar( array $array1, array $array2, array $keys ) {

		foreach( $keys as $key => $value ) {

			if( !isset( $array1[ $key ] ) || !isset( $array2[ $key ] ) || $array1[ $key ] != $array2[ $key ] ) {
				return false;
			}

		}

		return true;

	}

        public static function map($dato, $sCallBack)
        {
            if(!is_array( $dato ))
            {
                return $sCallBack($dato);
            }
            else
                {
                    $arrRetorno = array();
                    foreach ($dato as $clave => $valor)
                    {
                        $arrRetorno[$clave] = self::map($dato[$clave], $sCallBack);
                    }
                    return $arrRetorno;
                }
        }

	public static function merge() { // Holds all the arrays passed

		$params = func_get_args(); // First array is used as the base, everything else overwrites on it
		$return = array_shift( $params ); // Merge all arrays on the first array

		foreach( $params as $array ) {

			foreach( $array as $key => $value ) { // Numeric keyed values are added (unless already there)

				if( is_numeric( $key ) && ( !in_array( $value, $return ) ) ) {

					if( is_array( $value ) ) {
						$return[] = self::merge( $return[ $key ], $value );
					} else {
						$return[] = $value;
					}

				} else {

					if( isset( $return[ $key ] ) && is_array( $value ) && is_array( $return[ $key ] ) ) {
						$return[ $key ] = self::merge( $return[ $key ], $value );
					} else {
						$return[ $key ] = $value;
					}

				}

			}

		}

		return $return;

	}

        public static function unsetKeys ( $array = array(), $keys = array())
        {
                if( !is_array( $array ) )
                {
                    return $array;
                }

                $arrRetorno = $array;


                foreach( $keys as $key ) {

			if( isset( $arrRetorno[ $key ] ) ) {
				unset( $arrRetorno[ $key ] );
			}

		}

                return $arrRetorno;

        }

        public static function toQueryString ( $array = array() , $encode = false, $exclude = '', $addQMark = false)
        {
		if( !is_array( $array ) || count( $array ) == 0 )
                {
			return '';
		}

                if ( $exclude != '' )
                {
                        $aux = self::unsetKeys($array, explode (',', $exclude));
                }


		$params = array();

		foreach( $aux as $tag => $value ) {
			$params[] = $tag . '=' . rawurlencode( $value );
		}


		if( $encode ) {
			$qs = implode( '&amp;', $params );
		} else {
			$qs = implode( '&', $params );
		}

                if ( $addQMark )
                    return "?".$qs;
                else
                    return $qs;


        }
}

?>
