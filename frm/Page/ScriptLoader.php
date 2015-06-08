<?php
class Ftl_ScriptLoader{
        private static $_map = array(
            "ui"        => array("dependencies"=>"ui.core.js","loaded"=>false),
            "cookie"    => array("module"=>"JS_Cookie.js","dependencies"=>"jquery.cookie.js","loaded"=>false),
            "encoding"  => array("module"=>"JS_Encoding.js","loaded"=>false),
            "form"      => array("module"=>"JS_Form.js","dependencies"=>"jquery.form.js,jquery.validar.js,jquery.placeholder.js","loaded"=>false),
            "path"      => array("module"=>"JS_Path.js","loaded"=>false),
            "social"    => array("module"=>"JS_Social.js","loaded"=>false),

            "tooltip"   => array("module_key"=>"ui","dependencies" => "jquery.tooltip.js","loaded"=>false),
            "checkbox"  => array("module_key"=>"ui","dependencies" => "ui.checkbox.js","loaded"=>false),
            "checkbox"  => array("module_key"=>"ui","dependencies" => "ui.checkbox.js","loaded"=>false),
            "fancybox"  => array("module_key"=>"ui","plugin" => true,"folder"=>"fancybox","dependencies"=>"jquery.fancybox.js?v=2.0.6,jquery.fancybox.css?v=2.0.6","loaded"=>false),
            "paginate"  => array("plugin" => true,"folder"=>"paginate","dependencies"=>"jquery.paginate.js,jquery.paginate.css","loaded"=>false),
            "carrito"   => array("plugin" => true,"folder"=>"carrito","dependencies"=>"CarritoCompra.js,carrito.css","loaded"=>false),
            "fgmenu"    => array("plugin" => true,"folder"=>"fgmenu","dependencies"=>"fg.menu.css,fg.menu.js","module_key"=>"ui","loaded"=>false),
            //"jqueryui"  => array("module_key"=>"ui","plugin" => true,"folder"=>"jqueryui","dependencies"=>"JS_UI.js,ui.selectmenu.js,ui.selectmenu.css,jqueryui.css,jquery-ui.custom.css","module_key"=>"ui","loaded"=>false),
            "jqueryui"  => array("module_key"=>"ui","plugin" => true,"folder"=>"jqueryui","dependencies"=>"JS_UI.js,ui.selectmenu.js,ui.selectmenu.css,jqueryui.css,jquery-ui.custom.css","module_key"=>"ui","loaded"=>false),
            //"facebook"  => array("module_key"=>"social","dependencies"=>"facebook/Facebook.js,facebook/jquery.facebook.multifriend.select.css,facebook/jquery.facebook.multifriend.select.min.js","loaded"=>false),
            //"facebook"  => array("module_key"=>"social","dependencies"=>"facebook/fb_signed_request.js,facebook/Facebook.js,facebook/fbfriendselector.css,facebook/fbfriendselector.js","loaded"=>false),
            "facebook"  => array("module_key"=>"social","dependencies"=>"facebook/facebook.jquery.js,facebook/fb_signed_request.js","loaded"=>false),

        );





        public static function loadJSController($file,$dir=''){

            $str_scripts = "<script language=\"javascript\" type=\"text/javascript\" src=\"" . URL_ROOT . ($dir != "" ? $dir . "/" : $dir) . $file . "\"></script>\n";
            return $str_scripts;

        }

        public static function load ($modules){
            $str_scripts = "<script language=\"javascript\" type=\"text/javascript\" src=\"" . URL_ROOT . "js/jquery.min.js\"></script>\n";
            $str_scripts .= "<script language=\"javascript\" type=\"text/javascript\" src=\"" . URL_ROOT . "js/JS.js\"></script>\n";
            $str_scripts .= self::recursive($modules);
            return $str_scripts;
        }

        private static function recursive ($modules){
            $str_scripts = "";
            if ( $modules != "" ){

                $a_modules = explode(",",$modules);
                foreach ($a_modules as $key){

                    if ( array_key_exists( $key, self::$_map ) && !self::$_map[ $key ][ 'loaded' ]){

                        //Primero me fijo si tiene module_key
                        if ( isset ( self::$_map[ $key ][ 'module_key' ] ) ) {

                            $str_scripts .= self::recursive(self::$_map[ $key ][ 'module_key' ]);
                        }
                        //Segundo me fijo si tiene module para cargar
                        if ( isset ( self::$_map[ $key ][ 'module' ] ) ) {

                            $str_scripts .= "<script language=\"javascript\" type=\"text/javascript\" src=\"" . URL_ROOT . "js/modulos/" . self::$_map[ $key ][ 'module' ] ."\"></script>\n";

                        }
                        //Por ultimo me fijo si tiene dependences para cargar
                        if ( isset ( self::$_map[ $key ][ 'dependencies' ] ) ) {

                            $a_dep = explode(",",self::$_map[ $key ][ 'dependencies' ]);
                            foreach ($a_dep as $keyd){
                                

                                if ( stristr($keyd,".js") !== FALSE ){
                                    
                                    $str_scripts .= "<script language=\"javascript\" type=\"text/javascript\" src=\"" . ( isset ( self::$_map[ $key ][ 'plugin' ] ) && self::$_map[ $key ][ 'plugin' ] == true ? URL_ROOT . "js/plugins/" . self::$_map[ $key ][ 'folder' ] . "/" : URL_ROOT . "js/modulos/dependencias/"  )   . $keyd ."\"></script>\n";
                                    
                                } else {
                                    
                                    $str_scripts .= "<link rel=\"stylesheet\" href=\"" . ( isset ( self::$_map[ $key ][ 'plugin' ] ) && self::$_map[ $key ][ 'plugin' ] == true ? URL_ROOT . "js/plugins/" . self::$_map[ $key ][ 'folder' ] . "/" : URL_ROOT . "js/modulos/dependencias/"  )   . $keyd ."\" type=\"text/css\" media=\"screen\" />\n";
                                }


                            }
                        }
                        self::$_map[ $key ][ 'loaded' ] = true;
                    }

                }


            }
            return $str_scripts;

        }
}

