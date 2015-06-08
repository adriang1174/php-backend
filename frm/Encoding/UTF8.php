<?php

/**
 * Description of UTF8
 *
 * @author Luki
 */
class Ftl_UTF8 {
    //put your code here

    	public static function encode( $data ) {

		if( is_array( $data ) ) {

			foreach ( $data as $key => $val ) {
				$data[ $key ] = self::encode( $val );
			}

			return $data;

		}

		if( is_object( $data ) ) {

			$arr = array();
			foreach( $data as $key => $val ) {
				$arr[ $key ] = self::encode( $val );
			}

			return $arr;

		}

		if( is_string( $data ) ) {
			return utf8_encode( $data );
		}

		return $data;

	}

	public static function decode( $data ) {

		if( is_array( $data ) ) {

			foreach( $data as $key => $val ) {
				$data[ $key ] = self::decode( $val );
			}

			return $data;

		}

		if( is_object( $data ) ) {

			foreach( $data as $key => $val ) {
				$data->$key = self::decode( $val );
			}

			return $data;

		}

		if( is_string( $data ) ) {
			return utf8_decode( $data );
		}

		return $data;

	}


}
?>
