<?php

class Ftl_MimeType {

	// Default
	const APPLICATION_DEFAULT = 'application/octet-stream';

	// application
	const APPLICATION_CAB	= 'application/vnd.ms-cab-compressed';
	const APPLICATION_EXE	= 'application/x-msdownload';
	const APPLICATION_JS	= 'application/javascript';
	const APPLICATION_JSON	= 'application/json';
	const APPLICATION_MSI	= 'application/x-msdownload';
	const APPLICATION_RAR	= 'application/x-rar-compressed';
	const APPLICATION_SWF	= 'application/x-shockwave-flash';
	const APPLICATION_XML	= 'application/xml';
	const APPLICATION_ZIP	= 'application/zip';

	// text
	const TEXT_CSS			= 'text/css';
	const TEXT_HTM			= 'text/html';
	const TEXT_HTML			= 'text/html';
	const TEXT_PHP			= 'text/html';
	const TEXT_TXT			= 'text/plain';

	// images
	const IMAGE_BMP			= 'image/x-ms-bmp';
	const IMAGE_GIF			= 'image/gif';
	const IMAGE_ICO			= 'image/vnd.microsoft.icon';
	const IMAGE_JPE			= 'image/jpeg';
	const IMAGE_JPEG		= 'image/jpeg';
	const IMAGE_JPG			= 'image/jpeg';
	const IMAGE_PNG			= 'image/png';
	const IMAGE_SVG			= 'image/svg+xml';
	const IMAGE_SVGZ		= 'image/svg+xml';
	const IMAGE_TIF			= 'image/tiff';
	const IMAGE_TIFF		= 'image/tiff';

	// media
	const MEDIA_FLV			= 'video/x-flv';
	const MEDIA_MOV			= 'video/quicktime';
	const MEDIA_MP3			= 'audio/mpeg';
	const MEDIA_QT			= 'video/quicktime';

	// adobe
	const ADOBE_AI			= 'application/postscript';
	const ADOBE_EPS			= 'application/postscript';
	const ADOBE_PDF			= 'application/pdf';
	const ADOBE_PS			= 'application/postscript';
	const ADOBE_PSD			= 'image/vnd.adobe.photoshop';

	// ms/open office
	const OFFICE_DOC		= 'application/msword';
	const OFFICE_ODS		= 'application/vnd.oasis.opendocument.spreadsheet';
	const OFFICE_ODT		= 'application/vnd.oasis.opendocument.text';
	const OFFICE_PPT		= 'application/vnd.ms-powerpoint';
	const OFFICE_RTF		= 'application/rtf';
	const OFFICE_XLS		= 'application/vnd.ms-excel';

	private static $_mimeTypes = array(
		// application
		'cab'	=> self::APPLICATION_CAB,
		'exe'	=> self::APPLICATION_EXE,
		'js'	=> self::APPLICATION_JS,
		'json'	=> self::APPLICATION_JSON,
		'msi'	=> self::APPLICATION_MSI,
		'rar'	=> self::APPLICATION_RAR,
		'swf'	=> self::APPLICATION_SWF,
		'xml'	=> self::APPLICATION_XML,
		'zip'	=> self::APPLICATION_ZIP,

		// text
		'css'	=> self::TEXT_CSS,
		'htm'	=> self::TEXT_HTM,
		'html'	=> self::TEXT_HTML,
		'php'	=> self::TEXT_PHP,
		'txt'	=> self::TEXT_TXT,

		// images
		'png'	=> self::IMAGE_PNG,
		'jpe'	=> self::IMAGE_JPE,
		'jpeg'	=> self::IMAGE_JPEG,
		'jpg'	=> self::IMAGE_JPG,
		'gif'	=> self::IMAGE_GIF,
		'bmp'	=> self::IMAGE_BMP,
		'ico'	=> self::IMAGE_ICO,
		'tif'	=> self::IMAGE_TIF,
		'tiff'	=> self::IMAGE_TIFF,
		'svg'	=> self::IMAGE_SVG,
		'svgz'	=> self::IMAGE_SVGZ,

		// media
		'flv'	=> self::MEDIA_FLV,
		'mov'	=> self::MEDIA_MOV,
		'mp3'	=> self::MEDIA_MP3,
		'qt'	=> self::MEDIA_QT,

		// adobe
		'ai'	=> self::ADOBE_AI,
		'eps'	=> self::ADOBE_EPS,
		'pdf'	=> self::ADOBE_PDF,
		'ps'	=> self::ADOBE_PS,
		'psd'	=> self::ADOBE_PSD,

		// ms/open office
		'doc'	=> self::OFFICE_DOC,
		'ods'	=> self::OFFICE_ODS,
		'odt'	=> self::OFFICE_ODT,
		'ppt'	=> self::OFFICE_PPT,
		'rtf'	=> self::OFFICE_RTF,
		'xls'	=> self::OFFICE_XLS
	);

	public static function getMimeType( $file ) {

		if( function_exists( 'finfo_open' ) ) {

			$finfo = finfo_open( FILEINFO_MIME );
			$mt = finfo_file( $finfo, $file );
			finfo_close( $finfo );

			return $mt;

		}

                $ext = substr( $file, strrpos( $file , '.' ) + 1 ) ;

		if( array_key_exists( $ext, self::$_mimeTypes ) ) {
			return self::$_mimeTypes[ $ext ];
		}

		return self::APPLICATION_DEFAULT;

	}

}
