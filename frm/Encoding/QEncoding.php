<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of QEncoding
 *
 * @author Luki
 */
class Ftl_QEncoding {
    //put your code here

	public static function encode( $str ) {

		$found = false;

		for( $i = 0; $i < strlen( $str ); $i++ ) {

			if( ord( $str{ $i } ) >= 128 ) {
				$found = true;
				break;
			}

		}

		if( !$found ) {
			return $str;
		}


		$return = '=?ISO-8859-1?Q?';	// C=F3mo_Estas_=5F_ac=E1=3F?=

		for( $i = 0; $i < strlen( $str ); $i++ ) {

			$charCode = ord( $str{ $i } );

			if( $charCode == ord( ' ' ) ) {
				$return .= '_';
			} else if(
				( $charCode >= ord( 'a' ) && $charCode <= ord( 'z' ) )
				|| ( $charCode >= ord( 'A' ) && $charCode <= ord( 'Z' ) )
				|| ( $charCode >= ord( '0' ) && $charCode <= ord( '9' ) )
				) {
				$return .= $str{ $i };
			} else {
				$n = $charCode;
				$h = dechex( $n );
				$h = str_pad( $h, 2, STR_PAD_LEFT );
				$return .= '=' . $h;
			}

		}

		$return .= '?=';

		return $return;

	}


}
?>
