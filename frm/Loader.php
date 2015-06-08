<?php

class Ftl_Loader {

	const TYPE_LIBRARY		= 'library';
	const TYPE_CONTROLLER           = 'controller';
	const TYPE_CLASS                = 'class';

	private static $_CLASSES_MAP    = array
        (   
            self::TYPE_CONTROLLER => array(
                    "prefix"        => "Class_",
                    "classes"       => "/../clases/"
            ),

            self::TYPE_LIBRARY  => array
                (
                    "prefix"        => "Ftl_",
                    "classes"       => array
                        (
                            "Log"           => "/",
                            "Mail"          => "/",
                            

                            //Base de datos
                            "DB"            => "/",
                            "IDataBase"      => "/DB/",
                            "DataBase"      => "/DB/",
                            "MySql"         => "/DB/MySql/",
                            "MySqli"        => "/DB/MySqli/",
                            "PDO"           => "/DB/PDO/",
                            "PDOnew"        => "/DB/PDO/",

                            //Tipo de datos
                            "ArrayUtil"     => "/DataType/",
                            "StringUtil"    => "/DataType/",
                            "JsonUtil"      => "/DataType/",
                            "ObjectUtil"    => "/DataType/",
                            "DateTimeUtil"  => "/DataType/",

                            //Page
                            "Header"        => "/Page/",
                            "Redirect"      => "/Page/",
                            "ScriptLoader"  => "/Page/",

                            //Encoding
                            "UTF8"          => "/Encoding/",
                            "QEncoding"     => "/Encoding/",
                            "CharsetEncoding"=> "/Encoding/",

                            //Path
                            "Path"          => "/Path/",

                            //BO
                            "ListBO"        => "/BO/",
                            "SessionBO"     => "/BO/",
                            "PageBO"     => "/BO/",


                            //Classes
                            "Registrado"    => "/Classes/",
                            "UsuarioBO"     => "/Classes/",
                            "Response"      => "/Classes/",
                            "ClaseBase"     => "/Classes/",
                            "Foto"          => "/Classes/",

                            //IO
                            "IOHelper"      => "/IO/",

                            //Internationalization
                            "Language"      => "/Internationalization/",
                            "Countries"     => "/Internationalization/",

                            //File
                            "MimeType"      => "/File/",
                            "ImageInfo"     => "/File/",
                            "Image"         => "/File/",

                            //Social
                            "FacebookUtil"  => "/Social/Facebook/",

                            //Ecommerce
                            "MercadoPagoUtil"   => "/Ecommerce/MP/",
                            "Pedido"            => "/Ecommerce/",
                            "ArticuloPedido"    => "/Ecommerce/",
                            "EcommerceException"    => "/Ecommerce/",
                            "IPagoPedido"    => "/Ecommerce/"

                        )
                )
            
        );

	public function __construct() {

		spl_autoload_register( array( $this, self::TYPE_LIBRARY ) );
		spl_autoload_register( array( $this, self::TYPE_CONTROLLER ) );

	}

	public function library( $class ) {
		return $this->loadClass( $class, self::TYPE_LIBRARY );
	}
	public function controller( $class ) {
		return $this->loadClass( $class, self::TYPE_CONTROLLER );
	}

	private function loadClass( $class, $type ) {
            if ( $type == self::TYPE_LIBRARY){
		if( strpos($class, self::$_CLASSES_MAP[self::TYPE_LIBRARY]['prefix'] ) !== 0 ) {
			return false;
		}

		$path       = explode( '_', $class );
                $finalPath  = dirname( __FILE__ ).self::$_CLASSES_MAP[self::TYPE_LIBRARY]['classes'][$path[1]] . $path[1] . '.php';
            }else{
                $path       = explode( '_', $class );
                
                
                //Si es una clase controladora
		if( strpos($class, self::$_CLASSES_MAP[self::TYPE_CONTROLLER]['prefix'] ) !== 0 ) {
			return false;
		}                
		$path       = explode( '_', $class );
                $finalPath  = dirname( __FILE__ ).self::$_CLASSES_MAP[self::TYPE_CONTROLLER]['classes'] . $path[1] . '.php';
                
            }
            
            
            
                if( !file_exists( $finalPath ) )
                {
                    return false;
		}
                
		require_once $finalPath;

		return true;

	}


	private static $instance = null;

	public static function getInstance() {

        if( self::$instance === null ){
            self::$instance = new Ftl_Loader();
        }

        return self::$instance;

	}

}