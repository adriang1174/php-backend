<?php
/**
 * Description of JsonUtil
 *
 * @author Luki
 */
class Ftl_JsonUtil {
	private static $nodeLevel = 1;
	private static $json = '';

        public static function encode($data, $encondearEnUtf8= false)
        {
            if($encondearEnUtf8)
                return json_encode(Ftl_UTF8::encode ($data));
            else
                return json_encode($data);
        }

        public static function decode($data)
        {
            return json_decode($data);
        }

        /**
	 * Convert an Array (Associative or Numeric) to JSON.
	 * @param array Array to convert.
	 */
	public static function fromArray( $array ) {

		// $array = array() if $array isn't an array
		$array = ( is_array( $array ) ) ? $array : array();

		foreach( $array as $tag => $value ) {

			// If item is an array, then I have to descompose it. Else, I write the JSON key: value.
			if( is_array( $array[ $tag ] ) ) {

				// Child node level.
				++self::$nodeLevel;

				self::$json .= '{';
				// Descompose item array.
				self::fromArray( $array[ $tag ] );
				// Remove the lastest comma from the last JSON key.
				self::$json = substr( self::$json, 0, strlen( self::$json ) - 1 );
				self::$json .= '},';

				--self::$nodeLevel;

			} else {
				// JSON key: value item.
				self::$json .= '"' . $tag . '":"' . $value . '",';
			}

		}

		// If child node level is the first, then convertion was completed.
		if( self::$nodeLevel == 1 ) {
			// Remove the last comma.
			self::$json = substr( self::$json, 0, strlen( self::$json ) - 1 );
			// Convert ISO-8856-1 characters to UTF-8.
			#self::$json = iconv('iso-8859-1', 'utf-8', self::$json);
			self::$json = '{' . utf8_encode( self::$json ) . '}';

		}

		return self::$json;

	}
}
?>
