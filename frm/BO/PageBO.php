<?php
class Ftl_PageBO {

    private $scripts            = "fgmenu,fancybox,jqueryui,";
    private $jsController       = array();
    private $title              = "";
    private $session            = null;
   // private $showMyAccountMenu  = true;
    private $showMenu           = true;
    private $showHeader         = true;
    private $charsetEncoding    = Ftl_CharsetEncoding::UTF8;
    private $cacheable          = true;
    private $uiTheme            = "ui-lightness";

    public function getSession() {
        return $this->session;
    }

    public function setSession($session) {
        $this->session = $session;
    }

    
    public function  __construct() {
        $this->session = new Ftl_SessionBO();
        
    }

    public function setTitle($title){
        $this->title = $title;

    }

    public function loadSripts($scripts){
        $this->scripts .= $scripts;
    }

    public function loadJSController($file,$dir=''){
        array_push($this->jsController, array("file" => $file,"dir" => $dir));
    }
    
    public function setCharsetEncoding($charset=Ftl_CharsetEncoding::UTF8){
        //Ftl_Header::setCharsetEncoding($charset);
        $this->charsetEncoding = $charset;
    }

    public function setCacheable( $value ){
        //Ftl_Header::setNoCache();
        $this->cacheable = $value;
    }

    public function checkSession($idPerfil=null,$redirect='login.php'){

        if (!isset($this->session) || !$this->session->isLogged())
            Ftl_Redirect::toPage('login.php',true);


        if ( isset($idPerfil) && $this->session->getUser()->getIdPerfil() != $idPerfil ) {
            Ftl_Redirect::toPage($redirect,true);
        }


    }

//    public function showMyAccountMenu($show=null){
//        //Si me llega un valor distinto de NULL (true or false) entonces el metodo se usa para setear.
//        //Si me llega null se usa para retornar el valor
//        if (isset($show))
//            $this->showMyAccountMenu = $show;
//        else
//            return $this->showMyAccountMenu;
//    }
    public function showMenu($show=null){
        //Si me llega un valor distinto de NULL (true or false) entonces el metodo se usa para setear.
        //Si me llega null se usa para retornar el valor
        if (isset($show))
            $this->showMenu = $show;
        else
            return $this->showMenu;
    }
    public function showHeader($show=null){
        //Si me llega un valor distinto de NULL (true or false) entonces el metodo se usa para setear.
        //Si me llega null se usa para retornar el valor
        if (isset($show))
            $this->showHeader = $show;
        else
            return $this->showHeader;
    }

    public function showTop(){
        //require_once PATH_ADMIN  .  'includes'. DS . 'inc_session.php';
        require_once PATH_FRM   . DS . 'BO'. DS . 'html'. DS . 'inc_top.php';

    }

    public function showFoot(){
        //require_once PATH_ADMIN  .  'includes'. DS . 'inc_session.php';
        require_once PATH_FRM   . DS . 'BO'. DS . 'html'. DS . 'inc_bottom.php';
    }

}

?>
