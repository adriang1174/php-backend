<?php

class Mdi_Util_ShortUrl {

	public static function makeBitLyURLShortUrl( $url ) {

		if( !defined( 'CONFIG_BITLY_LOGIN' ) || !defined( 'CONFIG_BITLY_APPKEY' ) ) {
			return null;
		}

		$login		= CONFIG_BITLY_LOGIN;
		$appkey		= CONFIG_BITLY_APPKEY;
		$format		= 'json';
		$version	= '2.0.1';

		$bitly = 'http://api.bit.ly/shorten?' .
			'version='	. rawurlencode( $version ) .
			'&longUrl='	. rawurlencode( $url ) .
			'&login='	. rawurlencode( $login ) .
			'&apiKey='	. rawurlencode( $appkey ) .
			'&format='	. rawurlencode( $format );

		$response = Mdi_File::getFileContentsCurl( $bitly );

		if( strtolower( $format ) == 'json' ) {

			$json = @json_decode( $response, true );

			if( !isset( $json[ 'results' ][ $url ][ 'shortUrl' ] ) ) {
				return null;
			}

			return $json[ 'results' ][ $url ][ 'shortUrl' ];

		}

		return null;

	}

	public static function makeToLyShortUrl( $url ) {
		return Mdi_File::getFileContentsCurl( 'http://to.ly/api.php?json=0&longurl=' . rawurlencode( $url ) );
	}

	public static function makeShortUrl( $url ) {

		$link = self::makeToLyShortUrl( $url );

		if( !$link ) {

			$link = self::makeBitLyURLShortUrl( $url );

			if( !$link ) {
				return $url;
			}

		}

		return $link;

	}

}