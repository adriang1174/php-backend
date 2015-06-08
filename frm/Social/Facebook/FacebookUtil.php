<?php

require_once PATH_FRM."/Social/Facebook/src/facebook.php";

class NecesitaLoginFBException extends Exception {}


final class Ftl_FacebookUtil {

	private $facebook;
	private $session;
        private static $nroObjeto = 0;
        private $user;

        private static $instancia;

        // <editor-fold defaultstate="collapsed" desc="CONSTANTES">
        const GET_ALBUMS    = 'usuario.getAlbums';
        const CREATE_ALBUM  = 'crearAlbum';
        const GET_INFO      = 'usuario.getInfo';
        const GET_FRIENDS   = 'usuario.getAmigos';
        const ES_FAN        = 'usuario.esFan';
        const GET_PHOTOS    = 'album.getFotos';
        const UPLOAD_PHOTO  = 'subirFoto';
        const POST_FEED     = 'postFeed';


        const ALBUM_FIELDS  = 'aid,owner,cover_pid,name,created,modified,description,location,size,link,visible,modified_major,type,object_id';
        const USER_FIELDS   = 'uid,first_name,middle_name,last_name,name,pic_small,pic_big,pic_square,pic,affiliations,profile_update_time,timezone,religion,birthday,birthday_date,sex,hometown_location,meeting_sex,meeting_for';
        const PHOTO_FIELDS  = 'pid,aid,owner,src_small,src_small_height,src_small_width,src_big,src_big_height,src_big_width,src,src_height,src_width,link,caption,created,modified,object_id';
        const STREAM_FIELDS = 'post_id,source_id,updated_time,created_time,filter_key,attribution,actor_id,target_id,message,app_data,action_links,attachment,comments,likes,privacy,type,permalink';
        const SCOPES        = 'user_about_me,user_activities,user_birthday,user_education_history,user_events,user_groups,user_hometown,user_interests,user_likes,user_location,user_notes,user_online_presence,user_photo_video_tags,user_photos,user_relationships,user_relationship_details,user_religion_politics,user_status,user_videos,user_website,user_work_history,email,read_friendlists,read_insights,read_mailbox,read_requests,read_stream,xmpp_login,ads_management,user_checkins,publish_stream,create_event,rsvp_event,sms,offline_access,manage_pages';


        // </editor-fold>


	public function __construct(){

            self::$nroObjeto++;
            $this->facebook = self::getFacebookApi();
            $this->session  = null;
            $this->user = null;


        }

        public static function getFacebookApi(){
            if (!isset(self::$instancia)) {

                self::$instancia = new Facebook(
                    array(
                      'appId'  => FB_APP_ID,
                      'secret' => FB_APP_SECRET,
                      'fileUpload'  => true
                    )
                );


            }
            return self::$instancia;

        }

        public function login( $verificarPermisos=true ){

            try{

                $response = new Ftl_Response();
                $response->state = 0;

                //$this->printHttpHeader();

                $uid = $this->facebook->getUser();

                if ($uid)
                {

                    $fbCall = $this->call(array(
                        "method" => self::GET_INFO,
                        "params" => array(
                            "uid"       => $uid,
                            "fields"    => "uid,name,first_name,last_name,pic_square,email,birthday,birthday_date,sex"
                        )
                    ));


                    $this->setUser( $fbCall->data->{0} );

                    return true;


                }
                else
                {
                    if ($verificarPermisos)
                        $this->forceLogin();
                    else
                        return false;
                }

            }
            catch (FacebookApiException $e){

                $response->state    = $e->getCode();
                $response->message  = $e->getMessage();

                return $response;
                /*
                if (isset($err['error_code']) && $err['error_code'] == '190')
                    $this->forzarLoginFB();*/

            }
            catch (Exception $e)
            {

                $response->state    = $e->getCode();
                $response->message  = $e->getMessage();

                return $response;


            }


        }

        private function setUser($valor)
        {
            $this->user = $valor;
        }

        public function getUserId(){
            return $this->facebook->getUser();
        }

        public function getCurrentUser()
        {
            return $this->user;
        }

        public function setAccessToken($access_token){
            $this->facebook->setAccessToken($access_token);
        }

        public function getAccessToken()
        {
            return $this->facebook->getAccessToken();
        }

        public function api(/* polymorphic */) {
            try {
                    $args = func_get_args();
                    return call_user_func_array( array( $this->facebook, 'api' ), $args );

            } catch (FacebookApiException $e) {


            }

            return null;
        }

        public function createAlbum( $title, $message = '' ) {
                $response = new Ftl_Response();
                $response->state = 0;
                $this->facebook->setFileUploadSupport( true );

                $fbResp =  $this->api( '/me/albums', 'post', array(
                    'name'      => $title,
                    'message'	=> $message
                ) );

                if ($fbResp)
                {
                    $response->state    = 1;
                    $response->data     = Ftl_ArrayUtil::toObject( $fbResp );
                }

                return $response;


        }

        public function albumExists( $id ) {

                $response = new Ftl_Response();
                $response->state = 0;

                $fbResp =  $this->api( '', array(
                        'ids' => $id
                ) );

                if ($fbResp)
                {
                    $response->state    = 1;
                    $response->data     = Ftl_ArrayUtil::toObject($fbResp[$id]) ;
                }

                return $response;
        }

        public function uploadPicture( $albumId, $photoFile, $photoDescription = '',$tags=null ) {

                $response = new Ftl_Response();
                $response->state = 0;

                $this->facebook->setFileUploadSupport( true );

                $arguments = array(
                                    'message' => $photoDescription,
                                    'image' => '@' . realpath( $photoFile )
                );

                if ($tags)
                {
                    $arguments['tags'] = $tags;
                }
                $fbResp =  $this->api( '/' . $albumId . '/photos', 'post', $arguments );

                if ( $fbResp )
                {
                    $response->state    = 1;
                    $response->data     = Ftl_ArrayUtil::toObject($fbResp) ;
                }

                return $response;
        }

        public function addTag( $photoId, $to, $x = 0,$y = 0 ) {

                $response = new Ftl_Response();
                $response->state = 0;

                $arguments = array(
                                    'x'     => $x,
                                    'y'     => $y,
                                    'tag_text' => $to
                );

                $fbResp =  $this->api( '/' . $photoId . '/tags/', 'post', $arguments );

                if ( $fbResp )
                {
                    $response->state    = 1;
                    $response->data     = $fbResp ;
                }

                return $response;
        }


        public function postToFeed( $name, $description, $link, $picture = null, $caption = '', $message = '', $to = 'me' ) {

                $response = new Ftl_Response();
                $response->state = 0;

                $this->facebook->setFileUploadSupport( true );

                $params = array(
                        'name'          => $name,
                        'description'   => $description,
                        'link'          => $link,
                        'caption'       => $caption,
                        'message'       => $message
                );

                if ( $picture )
                    $params[ 'picture' ] = $picture;



                $fbResp =  $this->api( '/' . $to . '/feed', 'post', $params );



                if ( $fbResp )
                {
                    $response->state    = 1;
                    $response->data     = Ftl_ArrayUtil::toObject($fbResp) ;
                }

                return $response;
        }

        public function call($params)
        {
            $fql = array(
                'method'    => 'fql.query',
                'query'     => '',
                'callback'  => ''
            );

            $rest = array(
                'method'    => '',
                'callback'  => ''
            );


            $salida = array(
                "estado" => 0
            );

            if ($params != null){

                //try
                //{
                    switch ($params['method'])
                    {

                        case self::GET_INFO:
                            $fql['query'] = 'SELECT ' . (isset($params['params']) && isset($params['params']['fields']) ? $params['params']['fields'] : self::USER_FIELDS)
                                            . ' FROM user WHERE uid = ' . (isset($params['params']) && $params['params']['uid'] ? $params['params']['uid'] : 'me()');

                            if (isset($params['params']) && array_key_exists('limit', $params['params']))
                                    $fql['query'] .= ' LIMIT ' . $params['params']['limit'][0] . ',' . $params['params']['limit'][1];

                            $fbResponse = $this->api($fql);

                            return $this->output($params['method'],$fbResponse);


                        break;
                        // <editor-fold defaultstate="collapsed" desc="self::GET_ALBUMS">
                        case self::GET_ALBUMS:

                            $fql['query'] = 'SELECT ' .  ( isset($params['params']) && isset($params['params']['fields']) ? $params['params']['fields'] : self::ALBUM_FIELDS)
                                            . ' FROM album WHERE owner = ' . ( isset($params['params']) && isset($params['params']['uid']) ? $params['params']['uid'] : "me()");

                            if ( isset($params['params']) && array_key_exists('limit', $params['params']) )
                                    $fql['query'] .= ' LIMIT ' . $params['params']['limit'][0] . ',' . $params['params']['limit'][1];



                            $fbResponse = $this->api($fql);

                            return $this->output($params['method'],$fbResponse);
                            break;
                        // </editor-fold>/*
                        // <editor-fold defaultstate="collapsed" desc="self::GET_FRIENDS">
                        case self::GET_FRIENDS:
                            $fql['query'] = 'SELECT ' . (isset($params['params']) && isset($params['params']['fields']) ? $params['params']['fields'] : self::USER_FIELDS)
                                            . ' FROM user WHERE uid IN (SELECT uid2 FROM friend WHERE uid1 = ' . (isset($params['params']) && $params['params']['uid'] ? $params['params']['uid'] : 'me()') . ')';

                            if (isset($params['params']) && array_key_exists('limit', $params['params']))
                                    $fql['query'] .= ' LIMIT ' . $params['params']['limit'][0] . ',' . $params['params']['limit'][1];

                            $fbResponse = $this->api($fql);

                            return $this->output($params['method'],$fbResponse);



                            break;
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="self::GET_PHOTOS">
                        case self::GET_PHOTOS:
                            $fql['query'] = 'SELECT ' . (isset($params['params']) && isset($params['params']['fields']) ? $params['params']['fields'] : self::PHOTO_FIELDS)
                                            . ' FROM photo WHERE 1=1 ';

                            if (isset($params['params']) && array_key_exists('aid', $params['params']))
                                    $fql['query'] .= ' AND aid = \'' . $params['params']['aid'] . '\'';
                            if (isset($params['params']) && array_key_exists('album_object_id', $params['params']))
                                    $fql['query'] .= ' AND album_object_id = \'' . $params['params']['album_object_id'] . '\'';
                            if (isset($params['params']) && array_key_exists('pid', $params['params']))
                                    $fql['query'] .= ' AND pid = \'' . $params['params']['pid'] . '\'';
                            if (isset($params['params']) && array_key_exists('limit', $params['params']))
                                    $fql['query'] .= ' LIMIT ' . $params['params']['limit'][0] . ',' . $params['params']['limit'][1];

                            $fbResponse = $this->api($fql);

                            return $this->output($params['method'],$fbResponse);

                            break;
                        // </editor-fold>
                        // <editor-fold defaultstate="collapsed" desc="self::ES_FAN">
                        case self::ES_FAN:
                            $rest['method'] = 'pages.isFan';
                            $rest['page_id'] = $params['params']['pid'];
                            $fbResponse = $this->api($rest);
                            return $this->output($params['method'],$fbResponse);


                            break;
                        // </editor-fold>
    /*
                        // <editor-fold defaultstate="collapsed" desc="self::POST_FEED">
                        case self::POST_FEED:


                            $detalle        = array(
                                "message"       => "",
                                "picture"       => "",
                                "link"          => "",
                                "name"        => "",
                                "caption"       => "",
                                "description"   => "",
                                "actions"       => ""
                            );


                            if (array_key_exists('mensaje', $params['params']))
                                    $detalle['message'] = $params['params']['mensaje'];
                            if (array_key_exists('imagen', $params['params']))
                                    $detalle['picture'] = $params['params']['imagen'];
                            if (array_key_exists('link', $params['params']))
                                    $detalle['link'] = $params['params']['link'];
                            if (array_key_exists('nombre', $params['params']))
                                    $detalle['name'] = $params['params']['nombre'];
                            if (array_key_exists('caption', $params['params']))
                                    $detalle['caption'] = $params['params']['caption'];
                            if (array_key_exists('descripcion', $params['params']))
                                    $detalle['description'] = $params['params']['descripcion'];
                            if (array_key_exists('acciones', $params['params']))
                                    $detalle['actions'] = $params['params']['acciones'];
                            if (array_key_exists('target', $params['params']))
                                    $target = $params['params']['target'];
                            else
                                    $target = "me";

                            $fbResponse = $this->api("/{$target}/feed", 'post', $detalle);
                            return $this->output($params['method'],$fbResponse);

                            break;
                            // </editor-fold>*/
                    }

                /*}catch(Exception $ex){
                    $salida['error'] = array(
                        "code"      => $ex->getCode(),
                        "message"   => $ex->getMessage()
                    );

                    return $salida;
                }*/


            }


        }
        private function output($method,$fbResp)
        {

            $response = new Ftl_Response();
            $response->state = 0;

            switch ($method)
            {
                case self::GET_ALBUMS:
                case self::GET_FRIENDS:
                case self::GET_PHOTOS:
                case self::POST_FEED:
                case self::GET_INFO:
                case self::CREATE_ALBUM:
                case self::UPLOAD_PHOTO:

                    if (isset($fbResp) && is_array($fbResp))
                    {
                        if (array_key_exists('error', $fbResp))
                        {
                            $response->message = $fbResp['error'];
                        }
                        else
                        {
                            $response->state    = 1;
                            $response->data     = Ftl_ArrayUtil::toObject($fbResp);
                        }

                    }
                    else
                    {
                        $response->data     = null;
                    }


                    break;
                case self::ES_FAN:
                    if ( isset($fbResp) && $fbResp == '1' )
                        $response->state    = 1;


                    break;


            }
            return $response;
        }

        public static function getSignedRequest( )
        {
            $response = new Ftl_Response();
            $response->state = 0;

            if ( ! isset ( $_REQUEST['signed_request'] ) )
                return $response;

            list($encoded_sig, $payload) = explode('.', $_REQUEST['signed_request'], 2);


            // decode the data
            $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
            $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

            if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {

                $response->message = 'Unknown algorithm. Expected HMAC-SHA256';
                return $response;

            }

            // check sig
            $expected_sig = hash_hmac('sha256', $payload, FB_APP_SECRET, $raw = true);
            if ($sig !== $expected_sig) {

                $response->message = 'Bad Signed JSON signature!';
                return $response;

            }

            $response->state = 1;
            $response->data = Ftl_ArrayUtil::toObject($data);

            return $response;
        }

        public static function printHttpHeader(){
		header ( 'P3P:CP=\'IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT\'' );
		header ( 'Expires: Mon, 1 Jul 2006 21:30:00 GMT' );
		header ( 'Last-Modified: ' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
		header ( 'Cache-Control: no-store, no-cache, must-revalidate' );
		header ( 'Cache-Control: post-check=0, pre-check=0' );
		header ( 'Cache-Control: private' );
		header ( 'Pragma: no-cache' );
        }

        public function forceLogin()
        {
            if (isset($this->facebook)){
                $loginUrl = self::getFacebookApi()->getLoginUrl(array(
                    'redirect_uri'  => FB_SIG . (isset($_SERVER['QUERY_STRING']) ? "?".$_SERVER['QUERY_STRING'] : "") ,
                    'scope'         => FB_SCOPE
                ));
                echo "<script>window.top.location.href=\"".$loginUrl."\";</script>";
            }
        }

        public static function showFeedDialog($name,$description,$image=null)
        {
            $root = (Ftl_Path::isRunningUnderSSL() ? SSL_URL_ROOT : URL_ROOT);

            return '<div id="fbShare" style="left: 0px;position: absolute;top: 0;width: 520px;height:700px;position:absolute;z-index:9999;">
                    <div id="fbShareBoxContainer" style="background-color: #FFFFFF;border: 1px solid #003366;height: 160px;left: 7px;position: absolute;top: 100px;width: 500px;z-index: 100;">
                    <div id="fbShareTitle" style="background-color:#3b5998;color:#FFF;font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold;padding:5px;">'. $name .'</div>
                    <div id="fbShareImage" style="border:1px solid #999;width:80px;height:80px;left:20px;top:40px;position:absolute;"><img src="'. $image .'" width="80" height="80">
                  </div>
                    <div id="fbShareEnviando" style="display:none;background-color: #fff;color: #333333;font-family: Arial,Helvetica,sans-serif;font-size: 12px;height: 120px;left: 111px;position: absolute;top: 37px;width: 382px;z-index:999;background-image:url(' . $root . '/frm/Social/Facebook/img/sending.gif);background-repeat:no-repeat;background-position:center;text-align:center;">Estamos enviando las publicaciones...</div>
                    <div id="fbShareDescription" style="width:290px;height:80px;left:130px;top:40px;position:absolute;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#333;">'. $description .'</div>
                        <a id="fbShareEnviar" href="javascript:void(0);" style="background-color: #627aad;    color: #FFFFFF;    font-family: Arial,Helvetica,sans-serif;    font-size: 11px;    left: 130px;    padding: 4px;    position: absolute;    top: 128px;    width: 80px;	text-align:center;	text-transform:uppercase;	display:block;	text-decoration:none;	border:1px solid #1d4088;">Publicar</a>
                        <a id="fbShareCancelar" href="javascript:void(0);" style="background-color: #627aad;color: #FFFFFF;font-family: Arial,Helvetica,sans-serif;font-size: 11px;left: 244px;padding: 4px;position: absolute;top: 128px;width: 80px;text-align:center;text-transform:uppercase;display:block;text-decoration:none;border:1px solid #1d4088;">Cancelar</a>
                </div>
                <div id="fbShareBack" style="background-color:#fff;filter: alpha( opacity = 80 );opacity: .8;position: absolute;top: 0;width: 520px;height:700px;position:absolute;z-index:1;	">
                </div>
            </div>';
        }

/*
	public function tienePermisosNecesarios()
	{
		$required_perms = explode(',',FB_APP_PERMISOS);

		if(count($required_perms) < 1) {
			return(true);
		}

		//	hay que generar una query para cada permiso, pero se ejecutan todas juntas....
		$queries = array();
		foreach($required_perms as $perm) {
			$queries[$perm] = 'select ' . $perm . ' from permissions where uid=me()';
		}

		try {

			$result = $this->api(array(
				'method' 	=> 'fql.multiquery',
				'queries'	=>	$queries,
			));
		} catch (FacebookApiException $e)
		{
			return(false);
		}

		$okperms=array();

		foreach($result as $rs) {
			if((isset($rs['fql_result_set']))&&(isset($rs['fql_result_set'][0])))
			{
				foreach($rs['fql_result_set'][0] as $perm_name => $perm_value) {
					if($perm_value == '1') {
						$okperms[] = $perm_name;
					}
				}
			}
		}

		foreach($required_perms as $p)
		{
			if(!in_array($p,$okperms))
			{
				return(false);
			}
		}

		return(true);

	}

*/

}
/*

class Ftl_FacebookUtil {
    //put your code here
    private static $_cache = array();
    private static $_api;

    private static function getFacebookApi()
    {
            if(!self::$_api)
            {
                    self::$_api = new Facebook(array(
                      'appId'  => FB_APP_ID,
                      'secret' => FB_APP_SECRET,
                    ));
            }
            return self::$_api;
    }


    public static function login ()
    {
          try {

            $user_profile = self::getCurrentUser();
            return $user_profile;

          } catch (FacebookApiException $e) {

            $loginUrl = self::getFacebookApi()->getLoginUrl(array(
                'redirect_uri'  => "https://apps.facebook.com/".FB_NAMESPACE."/",
                'scope'         => FB_SCOPE
            ));
            echo "<script>window.top.location.href=\"".$loginUrl."\";</script>";
            exit;
          }

    }

    public static function getCurrentUser ( $redirect = SCRIPT_NAME )
    {
        try {

            return self::getFacebookApi()->api('/me');

        }catch (FacebookApiException $e) { throw $e; }

    }

    public static function getPicture ( $uid = 'me')
    {
        try {

            return self::getFacebookApi()->api("/$uid/picture");

        }catch (FacebookApiException $e) { throw $e; }

    }


    public static function createAlbum( $title, $message = '' ) {

            self::getFacebookApi()->setFileUploadSupport( true );

            return self::api( '/me/albums', 'post', array(
            'name'		=> $title,
            'message'	=> $message
            ) );

    }

    public static function albumExists( $id ) {

            return self::api( '', array(
                    'ids' => $id
            ) );

    }

    public static function uploadPicture( $albumId, $photoFile, $photoDescription = '' ) {

            self::getFacebookApi()->setFileUploadSupport( true );

            return self::api( '/' . $albumId . '/photos', 'post', array(
                    'message'	=> $photoDescription,
                    'image'     => '@' . realpath( $photoFile )
            ) );

    }

    public static function api($params)
    {
            try {
                    $facebook = self::getFacebookApi();

                    $args = func_get_args();
                    return call_user_func_array( array( $facebook, 'api' ), $args );

            } catch (FacebookApiException $e) {}

            return null;
    }

    public static function cachedApi($params)
    {
            if(!isset(self::$_cache[$params]))
            {
                    self::$_cache[$params]=self::api($params);
            }
            return(self::$_cache[$params]);
    }


    public static function printHttpHeader() {

            header ( 'P3P:CP=\'IDC DSP COR ADM DEVi TAIi PSA PSD IVAi IVDi CONi HIS OUR IND CNT\'' );
            header ( 'Expires: Mon, 1 Jul 2006 21:30:00 GMT' );
            header ( 'Last-Modified: ' . gmdate ( 'D, d M Y H:i:s' ) . ' GMT' );
            header ( 'Cache-Control: no-store, no-cache, must-revalidate' );
            header ( 'Cache-Control: post-check=0, pre-check=0' );
            header ( 'Cache-Control: private' );
            header ( 'Pragma: no-cache' );

    }

}*/
?>