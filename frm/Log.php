<?php

class Ftl_Log {

	private static $logEnabled = false;
	private static $log = array();

	public static function isEnabled() {
		return self::$logEnabled;
	}

	public static function enable() {
		self::$logEnabled = true;
	}

	public static function disable() {
		self::$logEnabled = false;
	}

	public static function push( $message, $type = Ftl_LogType::LOG, $class = null, $file = null, $line = null ) {

		if( !self::isEnabled() ) {
			return;
		}

		if( $class == null || $file == null || $line == null ) {

			$d = debug_backtrace();

			if( $class == null ) {
				$class = @$d[ 0 ][ 'class' ];
			}

			if( $file == null ) {
				$file = @$d[ 0 ][ 'file' ];
			}

			if( $line == null ) {
				$line = @$d[ 0 ][ 'line' ];
			}

		}

		if( is_array( $message ) ) {
			$message = implode( "\n", $message );
		}


                self::$log[]    = new Ftl_LogInfo   (
                                                        $message,
                                                        $type,
                                                        $class,
                                                        $class,
                                                        $line
                                                    );
                

		

	}

        public static function save ()
        {
            
        }

	public static function dump() {

		if( !self::isEnabled() ) {
			return;
		}

		echo '<xmp>';
		print_r(self::$log);

	}



}

class Ftl_LogInfo {

	private $message;
	private $type;
	private $class;
	private $file;
	private $line;
	private $microtime;

        public function __construct($message,$type,$class,$file,$line) {
            $this->message      = $message;
            $this->type         = $type;
            $this->class        = $class;
            $this->file         = $file;
            $this->line         = $line;
            $this->microtime    = microtime();
        }

}

class Ftl_LogType {

	const LOG			= 'LOG';

	const ERROR_LOWEST              = 'ERROR_LOWEST';
	const ERROR_LOW                 = 'ERROR_LOW';
	const ERROR_NORMAL              = 'ERROR_NORMAL';
	const ERROR_HIGH                = 'ERROR_HIGH';
	const ERROR_HIGHEST             = 'ERROR_HIGHEST';

}
?>