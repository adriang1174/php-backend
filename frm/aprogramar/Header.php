<?php

class Mdi_Header {

	public static function defaultHtmlOutput() {
		header( 'Content-Type: text/html; charset=utf-8' );
	}

	public static function noCache() {

		header( 'Expires: Tue, 01 Jul 2001 06:00:00 GMT' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s' ) . ' GMT' );
		header( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header( 'Cache-Control: post-check=0, pre-check=0', false );
		header( 'Pragma: no-cache' );

	}

	public static function setExpireTime( $expires, $lastModified ) {

		header( 'Cache-Control: max-age=' . $expires );

		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s', time() + $expires ) . ' GMT' );
		header( 'Last-Modified: ' . gmdate( 'D, d M Y H:i:s', $lastModified ) . ' GMT' );

	}

	public static function CVS( $filename = '', $export = true ) {

		header( 'Content-Type: text/csv; charset=utf-8' );

		if( $export ) {
			self::export( $filename, 'csv' );
		}

	}

	public static function XML( $filename = '', $export = false, $charset = 'utf-8' ) {

		header( 'Content-Type: text/xml; charset=' . $charset );

		if( $export ) {
			self::export( $filename, 'xml' );
		}

	}

	public static function XLS( $filename, $export = true ) {

		header( 'Content-Type: application/vnd.ms-excel' );

		if( $export ) {
			self::export( $filename, 'xls' );
		}

	}


	private static function export( $filename, $ext ) {

		if( $filename == '' ) {
			$filename = SCRIPT_FILENAME;
		} else {
			$filename = Mdi_DataType_String::toURL( $filename );
		}

		$filename .= '_' . date( 'Y-m-d-H-i-s' );

		header( 'Content-Disposition: attachment; filename=' . $filename . '.' . $ext );

	}

}