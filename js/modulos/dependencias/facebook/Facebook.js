function isArray(obj) {
    if (obj.constructor.toString().indexOf("Array") == -1)
        return false;
    else
        return true;
}

function isEmpty(obj) {
    for (var i in obj) {
        return false;
    }
    return true;
}
function trace(msg) {
    //    if(console)
    //        console.debug(msg);
}
function debug(obj) {
        if(console)
            console.log(obj);
}

var FBMetodo = {
    /*USUARIOS*/
    USUARIO_GET_INFO: 'usuario.getInfo',
    USUARIO_GET_AMIGOS: 'usuario.getAmigos',
    USUARIO_GET_ALBUMS: 'usuario.getAlbums',
    USUARIO_GET_LIKES: 'usuario.getLikes',
    USUARIO_COMPARTIR: 'usuario.compartir',
    USUARIO_AGREGAR_AMIGO: 'usuario.agregarAmigo',
    USUARIO_PUBLICAR: 'Usuario_publicar',
    USUARIO_POST_FEED: 'Usuario_postearFeed',
    USUARIO_GET_PERMISOS: 'usuario.getPermisos',
    USUARIO_GET_FEED: 'Usuario.getFeed',
    USUARIO_ELIMINAR_FEED: 'Usuario.eliminarFeed',
    USUARIO_GET_MSJ_RECIBIDOS: 'Usuario.getMensajesRecibidos',
    USUARIO_GET_MSJ_ENVIADOS: 'Usuario.getMensajesEnviados',
    USUARIO_ME_GUSTA: 'Usuario.meGusta',
    USUARIO_COMENTAR: 'Usuario.comentar',
    USUARIO_SET_ESTADO: 'Usuario.setEstado',
    USUARIO_POST_LINK: 'Usuario.postLink',
    USUARIO_GET_LINKS: 'Usuario.getLinks',
    USUARIO_GET_EVENTOS: 'Usuario.getEventos',



    USUARIO_GET_FOTOS_TAG: 'Usuario.getFotosTag',
    /*ALBUMS*/
    ALBUM_GET_INFO: 'album.getInfo',
    ALBUM_CREAR_NUEVO: 'album.crearNuevo',
    ALBUM_GET_FOTOS: 'album.getFotos',

    /*FOTOS*/
    FOTO_GET_INFO: 'foto.getInfo',
    FOTO_SUBIR_FOTO: 'foto.subir',
    FOTO_ETIQUETAR: 'foto.etiquetar',

    /*PAGES*/
    PAGE_GET_FEED: 'Page.getFeed',
    PAGE_ES_FAN: 'Page.esFan'
    /*NUEVA IMPLEMENTACION*/

}

var FBCampos = {
    ALBUM: 'aid,owner,cover_pid,name,created,modified,description,location,size,link,visible,modified_major,type,object_id',
    USUARIO: 'uid,first_name,middle_name,last_name,name,pic_small,pic_big,pic_square,pic,affiliations,profile_update_time,timezone,religion,birthday,birthday_date,sex,hometown_location,meeting_sex,meeting_for',
    FOTO: 'pid,aid,owner,src_small,src_small_height,src_small_width,src_big,src_big_height,src_big_width,src,src_height,src_width,link,caption,created,modified,object_id',
    STREAM: 'post_id,source_id,updated_time,created_time,filter_key,attribution,actor_id,target_id,message,app_data,action_links,attachment,comments,likes,privacy,type,permalink',
    PERMISOS: 'user_about_me,user_activities,user_birthday,user_education_history,user_events,user_groups,user_hometown,user_interests,user_likes,user_location,user_notes,user_online_presence,user_photo_video_tags,user_photos,user_relationships,user_relationship_details,user_religion_politics,user_status,user_videos,user_website,user_work_history,email,read_friendlists,read_insights,read_mailbox,read_requests,read_stream,xmpp_login,ads_management,user_checkins,publish_stream,create_event,rsvp_event,sms,offline_access,manage_pages',
    EVENTO: 'eid,name,tagline,nid,pic_small,pic_big,pic,host,description,event_type,event_subtype,start_time,end_time,creator,update_time,location,venue,privacy,hide_guest_list'
}

function armarSalida(response, metodo) {

    var respuesta;

    metodo = (metodo != null ? metodo : '');
    
    switch (metodo) {
        case FBMetodo.USUARIO_AGREGAR_AMIGO:
            respuesta = {
                estado: response && !response.error && !response.error_code && response.action ? 1 : 0,
                datos: null,
                error: response && (response.error || response.error_code) ? (response.error ? response.error : response.error_msg) : null
            };
            break;
        case FBMetodo.USUARIO_COMPARTIR:
            respuesta = {
                estado: response != null && !response.error && !response.error_code ? 1 : 0,
                datos: null,
                error: response && (response.error || response.error_code) ? (response.error ? response.error : response.error_msg) : null

            };
            break;
        case FBMetodo.USUARIO_SET_ESTADO:
            respuesta = {
                estado: response != null && !response.error && !response.error_code ? 1 : 0,
                datos: null,
                error: response && (response.error || response.error_code) ? (response.error ? response.error : response.error_msg) : null
            };
            break;
        case FBMetodo.USUARIO_ELIMINAR_FEED:
            respuesta = {
                estado: response != null && response.error == null && !response.error_code ? 1 : 0,
                datos: null,
                error: response && response.error ? response.error : null
            };
            break;
        case FBMetodo.USUARIO_ME_GUSTA:
            respuesta = {
                estado: response != null && response.error == null && !response.error_code ? 1 : 0,
                datos: null,
                error: response && response.error ? response.error : null
            };
            break;
        case FBMetodo.USUARIO_COMENTAR:
            respuesta = {
                estado: response != null && response.error == null && !response.error_code ? 1 : 0,
                datos: null,
                error: response && response.error ? response.error : null
            };
            break;
        case FBMetodo.USUARIO_GET_PERMISOS:
            respuesta = {
                estado: response && !response.error && !isEmpty(response) ? 1 : 0,
                datos: response ? response[0] : null,
                error: response && response.error ? response.error : null
            };
            break;
        case FBMetodo.ALBUM_CREAR_NUEVO:
            respuesta = {
                estado: response && response.id ? 1 : 0,
                datos: response && !response.error ? response : null,
                error: response && response.error ? response.error : null
            };
            break;
        case FBMetodo.FOTO_SUBIR_FOTO:
            respuesta = {
                estado: response.estado,
                datos: response.estado == 1 ? response.datos : null,
                error: response.estado == 0 && response.error ? response.error : null
            };
            break;
        case FBMetodo.FOTO_ETIQUETAR:
            respuesta = {
                estado: response == true && !response.error ? 1 : 0,
                datos: !response.error ? response : null,
                error: response && response.error ? response.error : null
            };
            break;
        case FBMetodo.USUARIO_POST_LINK:

            respuesta = {
                estado: 1,
                datos: response && response.data ? response.data : null,
                error: response && response.error ? response.error : null
            };
            break;
        case FBMetodo.PAGE_ES_FAN:

            respuesta = {
                estado: response != null && !response.error && !response.error_code ? 1 : 0,
                datos: response != null && !response.error && !response.error_code ? (response ? 1 : 0) : null,
                error: response && (response.error || response.error_code) ? (response.error ? response.error : response.error_msg) : null
            };
            break;
        case FBMetodo.USUARIO_GET_ALBUMS:

            respuesta = {
                estado: response && response.data && response.data.length > 0 ? 1 : 0,
                datos: response && response.data && response.data.length > 0 ? response.data : null,
                error: response && (response.error || response.error_code) ? (response.error ? response.error : response.error_msg) : null
            };
            
            if (response.paging){
                respuesta.paginado = response.paging;
            }
            
            break;
        default:
           
            respuesta = {
                estado: response && !response.error && !isEmpty(response) ? 1 : 0,
                datos: response && response.data ? response.data : response,
                error: response && response.error ? response.error : null
            };
            break;
    }
    trace(respuesta);
    return respuesta;
}


WFB = {
    /*PROPIEDADES PRIVADA*/
    _app: null,
    _session: null,

    /*CONSTANTES*/


    /*METODOS Y FUNCIONES*/
    getApp: function () {
        debug('->getApp');
        return _app;
    },
    getSession: function () {
        debug('->getSesion');
        return _session;
    },
    setApp: function (param) {
        debug('->setApp');
        _app = jQuery.extend({
            id: null,
            key: null,
            secret: null,
            nombre: '',
            permisos: ''
        }, param);
    },
    setSession: function (param) {
        debug('->setSesion');
        _session = jQuery.extend({
            id: null,
            signedRequest: null,
            token: null,
            estado: '',
            permisos: null
        }, param);
    },

    init: function (param) {
        debug('init');
        _app = jQuery.extend({
            id: null,
            key: null,
            secret: null,
            nombre: '',
            permisos: '',
            canal: ''
        }, param);

        FB.init({ appId: _app.id, status: true, cookie: true, xfbml: true, channelUrl: _app.canal,version : 'v2.0' });
        return this;
    },
    manejoSession: function (response) {
        debug('->manejoSession');
        
        if (response.authResponse != null) {
            
            WFB.setSession({
                id: response.authResponse.userID,
                signedRequest: response.authResponse.signedRequest,
                token: response.authResponse.accessToken,
                estado: response.status,
                permisos: (response.perms != null) ? response.perms : null
            });
        } else {
            WFB.setSession({
                estado: response.status
            });
        }
    },
    getEstadoLogin: function (param) {
        debug('->getEstadoLogin');
        FB.getLoginStatus(function (response) {
            if (response.status != 'connected'){
                WFB.login(param);
            }else{
                WFB.manejoSession(response);
                if (param.alFinalizar != null && param.alFinalizar != '') param.alFinalizar(response);
            }
        });
    },
    login: function (param) {
        debug('->login');
        
        param = jQuery.extend({
            popup: true
        }, param);        
        
        if (param.popup == true) {
            FB.login(function (response) {
                WFB.manejoSession(response);
                if (param.alFinalizar != null && param.alFinalizar != '') param.alFinalizar(response);
            }, { scope: WFB.getApp().permisos.join(',') });

        } else {
            var oauth_url = "http://www.facebook.com/dialog/oauth/?"
                            + "client_id=" + WFB.getApp().id
                            + "&redirect_uri=" + param.oauthRedirect
                            + "&scope=" + WFB.getApp().permisos.join(',');
            location.href = oauth_url;

        }

    },
    logout: function (param) {
        debug('->logout');
        FB.logout(function (response) {
            WFB.manejoSession(response);
            if (param.alFinalizar != null && param.alFinalizar != '') param.alFinalizar(WFB.getSession());
        });
    },
    parseSignedRequest:  function ($signed_request) {
          list($encoded_sig, $payload) = explode('.', $signed_request, 2); 

          // decode the data
          $sig = base64_url_decode($encoded_sig);
          $data = json_decode(base64_url_decode($payload), true);

          return {data:$data,sig:$sig};
    },    
    getTokenLargo: function (param){
        debug('->getTokenLargo');        
        JS.ajax.llamada({
            url: 'js/modulos/dependencias/facebook/getlonaccesstoken.php',
            data:{
                access_token: WFB.getSession().token
            },
            alFinalizar:function(r){
                if (r.state == 1){
                    WFB.setSession({token:r.data.access_token});
                }
                if (param.alFinalizar != null && param.alFinalizar != '') param.alFinalizar(r);
            }
        });        
        
        
    },
    obtenerListadoAmigos: function (params) {
        debug('->obtenerListadoAmigos');

        if (params.campos == undefined || params.campos == '') {
            params.campos = 'uid, name, pic_square, email, sex, birthday_date';
        }

        WFB.llamada({
            metodo: FBMetodo.USUARIO_GET_AMIGOS,
            parametros: params,
            alFinalizar: params.alFinalizar
        });

    },
    subirFoto: function (params) {

        _params = jQuery.extend({}, params);

        if (params.album == undefined || (params.album != undefined && (params.album.aid != undefined || params.album.nombre == undefined))) {

            if (params.album != undefined)
                _params.aid = params.album.aid;

            WFB.llamada({
                metodo: FBMetodo.FOTO_SUBIR_FOTO,
                parametros: _params,
                alFinalizar: _params.alFinalizar
            });

        } else if (params.album != undefined && params.album.nombre != undefined) {

            //Si tengo el nombre del album entonces busco su id
            _params.campos = 'object_id';
            _params.nombre = params.album.nombre;
            _params.modo = 'fql';


            WFB.llamada({

                metodo: FBMetodo.USUARIO_GET_ALBUMS,
                parametros: _params,
                alFinalizar: function (respAlbum) {
                    
                    if (respAlbum.estado == 1) {
                        
                        params.album.aid = respAlbum.data[0].object_id;
                        WFB.subirFoto(params);

                    } else {
                        WFB.crearAlbum({
                            nombre: params.album.nombre,
                            alFinalizar: function (respAlbum) {

                                if (respAlbum.estado == 1) {
                                    params.album.aid = respAlbum.datos.id;
                                    WFB.subirFoto(params);                                    
                                }

                            }
                        });

                    }
                }


            });


        }

    },
    crearAlbum: function (params) {
        debug('->crearAlbum');
        WFB.llamada({

            metodo: FBMetodo.ALBUM_CREAR_NUEVO,
            parametros: params,
            alFinalizar: params.alFinalizar

        });
    },
    obtenerAlbum: function (params) {
        debug('->obtenerAlbum');
        _params = params;
        _params.campos = 'object_id';

        if (_params.aid != undefined) {

            //Si tengo el ID busco por graph api los datos de ese album. El campo aid tiene mayor prioridad que el campo nombre.

            FB.api("/" + _params.aid, function (response) {
                if (_params.alFinalizar) _params.alFinalizar(armarSalida(response));
            });


        } else if (_params.nombre != undefined) {

            //Si tengo el nombre:
            // 1. Busco el album por nombre:
            // 1.1. Si existe obtengo el object_id y hago la busqueda del album por id por open graph
            debug('->BuscarAlbumPorNombre');
            _params.aid = null;
            WFB.llamada({

                metodo: FBMetodo.USUARIO_GET_ALBUMS,
                parametros: _params,
                alFinalizar: function (respAlbum) {

                    if (respAlbum.estado == 1) {

                        _params.aid = respAlbum.datos[0].object_id;
                        WFB.obtenerAlbum(_params);

                    } else {

                        if (_params.alFinalizar)
                            _params.alFinalizar(respAlbum);
                    }
                }

            });

        }

    },
    obtenerListadoAlbums: function (params) {

        debug('->obtenerListadoAlbums');

        WFB.llamada({

            metodo: FBMetodo.USUARIO_GET_ALBUMS,
            parametros: params,
            alFinalizar: params.alFinalizar

        });

    },
    obtenerFoto: function (params) {
        debug('->obtenerFoto');
        WFB.llamada({

            metodo: FBMetodo.ALBUM_GET_FOTOS,
            parametros: params,
            alFinalizar: params.alFinalizar

        });

    },
    obtenerListadoFotos: function (params) {
        debug('->obtenerListadoFotos');


        WFB.llamada({

            metodo: FBMetodo.ALBUM_GET_FOTOS,
            parametros: params,
            alFinalizar: params.alFinalizar

        });
    },
    etiquetarFoto: function (params) {

        debug('->etiquetarFoto');
        /*Ejemplo:
        WFB.etiquetarFoto({
        pid: '277052055724698',
        tags:[{tag_uid:"100003659526919",x: 44,y: 44},{tag_uid:"1519789869",x: 0,y: 0}],
        alFinalizar: function(resp){
        console.debug(resp);
        }
        });
        */

        WFB.llamada({

            metodo: FBMetodo.FOTO_ETIQUETAR,
            parametros: params,
            alFinalizar: params.alFinalizar

        });

    },
    esFan: function (oid,alFinalizar){
        WFB.llamada({

            metodo: FBMetodo.USUARIO_GET_LIKES,
            parametros: {oid:oid},
            alFinalizar: alFinalizar

        });        
    },
    llamada: function (opciones) {
        var sQuery = '';
        _opt = jQuery.extend({
            metodo: null,
            parametros: null,
            alFinalizar: null,
            traeCampos: function () {
                return this.parametros != null && this.parametros.campos != null && this.parametros.campos != '';
            },
            traeDatos: function () {
                return this.parametros != null && this.parametros.datos != null;
            }
        }, opciones);

        debug(_opt.metodo);
        switch (_opt.metodo) {
            /*
            ***************************************************************************************
            * USUARIO
            ***************************************************************************************
            */ 
            case FBMetodo.USUARIO_GET_INFO:
                sQuery = "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.USUARIO);
                sQuery += " from user where uid= " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : 'me()');
                debug('query:' + sQuery);

                FB.api({
                    method: 'fql.query',
                    query: sQuery
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response)); });
                break;

            case FBMetodo.USUARIO_GET_AMIGOS:
                sQuery = "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.USUARIO);
                sQuery += " from user where uid in (select uid2 from friend where uid1 = " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : 'me()') + ")";
                debug('query:' + sQuery);

                FB.api({
                    method: 'fql.query',
                    query: sQuery
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response)); });

                break;
            case FBMetodo.USUARIO_GET_ALBUMS:

                modo = (_opt.parametros.modo ? _opt.parametros.modo : 'graph');
                sLimit = '';


                if (modo == 'graph') {

                    if (_opt.parametros != null && _opt.parametros.paginacion) {

                        if (_opt.parametros.paginacion.length == 1) {
                            sLimit = "limit=" + _opt.parametros.paginacion[0];
                        } else if (_opt.parametros.paginacion.length > 1) {
                            sLimit = "limit=" + _opt.parametros.paginacion[0] + "&offset=" + _opt.parametros.paginacion[1];
                        }

                    }


                    sQuery = '/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/albums' + (sLimit.length > 0 ? '?' + sLimit : '');
                    debug('query:' + sQuery + (sLimit.length > 0 ? '&' + sLimit : ''));



                    FB.api(sQuery, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo)); });

                } else {

                    sQuery = "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.ALBUM);
                    sQuery += " from album where owner  = " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id)

                    if (_opt.parametros != null && _opt.parametros.aid)
                        sQuery += ' and object_id =\'' + _opt.parametros.aid + '\'';
                    if (_opt.parametros != null && _opt.parametros.nombre)
                        sQuery += ' and name =\'' + _opt.parametros.nombre + '\'';


                    if (_opt.parametros != null && _opt.parametros.paginacion) {

                        if (_opt.parametros.paginacion.length == 1) {
                            sLimit = "limit " + _opt.parametros.paginacion[0];
                        } else if (_opt.parametros.paginacion.length > 1) {
                            sLimit = "limit " + _opt.parametros.paginacion[0] + " offset " + _opt.parametros.paginacion[1];
                        }

                    }


                    debug('query:' + sQuery + sLimit);
                    FB.api({
                        method: 'fql.query',
                        query: sQuery + sLimit
                    }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response,_opt.metodo)); });



                }



                break;
            case FBMetodo.USUARIO_GET_MSJ_RECIBIDOS:
                sQuery = "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.STREAM);
                sQuery += " from stream where source_id = " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + " AND source_id = target_id AND message != \"\" ";
                if (_opt.parametros != null && _opt.parametros.uidAmigo)
                    sQuery += ' and actor_id = ' + _opt.parametros.uidAmigo;

                if (_opt.parametros != null && _opt.parametros.fechaDesde)
                    sQuery += ' and created_time >= ' + _opt.parametros.fechaDesde;

                if (_opt.parametros != null && _opt.parametros.fechaHasta)
                    sQuery += ' and created_time <= ' + _opt.parametros.fechaHasta;

                trace(sQuery);

                FB.api({
                    method: 'fql.query',
                    query: sQuery
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response)); });

                break;
            case FBMetodo.USUARIO_GET_MSJ_ENVIADOS:
                sQuery = "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.STREAM);
                sQuery += " from stream where filter_key = 'nf' and actor_id = " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + " and message != ''";
                if (_opt.parametros != null && _opt.parametros.uidAmigo)
                    sQuery += ' and target_id = ' + _opt.parametros.uidAmigo;

                if (_opt.parametros != null && _opt.parametros.fechaDesde)
                    sQuery += ' and created_time >= ' + _opt.parametros.fechaDesde;

                if (_opt.parametros != null && _opt.parametros.fechaHasta)
                    sQuery += ' and created_time <= ' + _opt.parametros.fechaHasta;

                trace(sQuery);

                FB.api({
                    method: 'fql.query',
                    query: sQuery
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response)); });

                break;
            case FBMetodo.USUARIO_GET_LIKES:
                FB.api(
                                    '/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/likes/' + ((_opt.parametros != null && _opt.parametros.oid != null) ? _opt.parametros.oid : '' ) + '?access_token=' + WFB.getSession().token
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response)); });
                break;
            case FBMetodo.USUARIO_PUBLICAR:
                var publish = null;

                publish = {
                    //                                method      : 'feed',
                    //                                display     : (_opt.parametros.modo ? _opt.parametros.modo : 'iframe'),
                    from: (_opt.parametros.de ? _opt.parametros.de : null),
                    to: (_opt.parametros.a ? _opt.parametros.a : null),
                    message: (_opt.parametros.mensaje ? _opt.parametros.mensaje : null),
                    link: (_opt.parametros.link ? _opt.parametros.link : null),
                    picture: (_opt.parametros.imagen ? _opt.parametros.imagen : null),
                    source: (_opt.parametros.fuente ? _opt.parametros.fuente : null),
                    name: (_opt.parametros.nombre ? _opt.parametros.nombre : null),
                    caption: (_opt.parametros.caption ? _opt.parametros.caption : null),
                    description: (_opt.parametros.descripcion ? _opt.parametros.descripcion : null),
                    properties: (_opt.parametros.propiedades ? _opt.parametros.propiedades : null),
                    actions: (_opt.parametros.acciones ? _opt.parametros.acciones : null)

                };

                modo = (_opt.parametros.modo ? _opt.parametros.modo : 'iframe');
                if (modo == 'graph') {
                    FB.api(
                                        '/' + (_opt.parametros.a ? _opt.parametros.a : WFB.getSession().id) + '/feed?access_token=' + WFB.getSession().token
                                        , 'post'
                                        , publish
                                    , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                }
                else {
                    publish.method = 'feed';
                    publish.display = modo;

                    //trace(publish);

                    FB.ui(
                                            publish
                                    , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });


                }



                break;
            case FBMetodo.USUARIO_GET_FEED:

                if (_opt.parametros != null && _opt.parametros.soloMios) {
                    //Si solo quiero traer lo que postie en mi muro.
                    sQuery = "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.STREAM);
                    sQuery += " from stream where source_id = " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + " AND source_id = actor_id ";

                    if (_opt.parametros.soloConMensaje) {
                        //Si quiero traer solo los que tengan un mensaje escrito por mi. (Para diferenciar del post comun)
                        sQuery += " AND message != \"\" ";
                    }
                    if (_opt.parametros != null && _opt.parametros.paginacion)
                        sQuery += " limit " + _opt.parametros.paginacion[0] + " offset " + _opt.parametros.paginacion[1];

                    trace(sQuery);

                    FB.api({
                        method: 'fql.query',
                        query: sQuery
                    }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response)); });

                } else {


                    sQuery = "limit=" + (_opt.parametros != null && _opt.parametros.paginacion ? _opt.parametros.paginacion[0] : "25");
                    sQuery += "&offset=" + (_opt.parametros != null && _opt.parametros.paginacion ? _opt.parametros.paginacion[1] : "0");

                    debug('/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/feed?' + sQuery + '&access_token=' + WFB.getSession().token);

                    FB.api(
                                        '/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/feed?' + sQuery + '&access_token=' + WFB.getSession().token
                            , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response)); });

                }

                break;
            case FBMetodo.USUARIO_ELIMINAR_FEED:
                FB.api(
                                    _opt.parametros.id
                                    , 'delete'
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            case FBMetodo.USUARIO_POST_FEED:
                FB.api(
                                    '/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/feed?access_token=' + WFB.getSession().token
                                    , 'post'
                                    , {
                                        message: _opt.parametros.mensaje,
                                        picture: _opt.parametros.imagen,
                                        link: _opt.parametros.link,
                                        name: _opt.parametros.nombre,
                                        caption: _opt.parametros.subtitulo,
                                        description: _opt.parametros.descripcion,
                                        source: _opt.parametros.fuente,
                                        actions: _opt.parametros.links

                                    }
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            case FBMetodo.USUARIO_AGREGAR_AMIGO:
                var add = {
                    method: 'friends.add',
                    id: _opt.parametros.uid
                }
                FB.ui(
                            add
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            case FBMetodo.USUARIO_COMPARTIR:
                var share = {
                    method: 'stream.share',
                    u: _opt.parametros.url
                };

                FB.ui(
                            share
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            case FBMetodo.USUARIO_GET_PERMISOS:
                sQuery = "select " + (_opt.parametros != null && _opt.parametros.permisos ? _opt.parametros.permisos : FBCampos.PERMISOS);
                sQuery += " from permissions where uid = me()";
                debug('query:' + sQuery);

                FB.api({
                    method: 'fql.query',
                    query: sQuery
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            case FBMetodo.USUARIO_ME_GUSTA:
                FB.api(
                                    '/' + _opt.parametros.oid + '/likes?access_token=' + WFB.getSession().token
                                    , 'post'
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            case FBMetodo.USUARIO_COMENTAR:
                FB.api(
                                    '/' + _opt.parametros.oid + '/comments?access_token=' + WFB.getSession().token
                                    , 'post'
                                    , { message: _opt.parametros.comentario }
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            case FBMetodo.USUARIO_SET_ESTADO:
                FB.api({
                    method: 'status.set',
                    status: _opt.parametros.estado
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            case FBMetodo.USUARIO_POST_LINK:
                FB.api(
                                    '/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/links?access_token=' + WFB.getSession().token
                                    , 'post'
                                    , {
                                        message: _opt.parametros.mensaje,
                                        link: _opt.parametros.link

                                    }
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            case FBMetodo.USUARIO_GET_LINKS:
                sQuery = "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.STREAM);
                sQuery += " from stream where filter_key IN (SELECT filter_key FROM stream_filter WHERE uid= " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + " and name = \"Links\") AND actor_id = " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id);
                trace(sQuery);

                FB.api({
                    method: 'fql.query',
                    query: sQuery
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response)); });

                break;
            case FBMetodo.USUARIO_GET_EVENTOS:
                sQuery = "select eid,rsvp_status";
                sQuery += " from event_member where uid = " + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id);

                if (_opt.parametros != null && _opt.parametros.estado)
                    sQuery += " and rsvp_status = '" + opt.parametros.estado + "'";

                if (_opt.parametros != null && _opt.parametros.paginacion)
                    sQuery += " limit " + _opt.parametros.paginacion[0] + " offset " + _opt.parametros.paginacion[1];

                trace(sQuery);

                FB.api({
                    method: 'fql.query',
                    query: sQuery
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response)); });
                break;
            case FBMetodo.USUARIO_GET_FOTOS_TAG:

                sQuery = "limit=" + (_opt.parametros != null && _opt.parametros.paginacion ? _opt.parametros.paginacion[0] : "25");
                sQuery += "&offset=" + (_opt.parametros != null && _opt.parametros.paginacion ? _opt.parametros.paginacion[1] : "0");

                debug('/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/photos?' + sQuery + '&access_token=' + WFB.getSession().token);

                FB.api(
                                    '/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/photos?' + sQuery + '&access_token=' + WFB.getSession().token
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response)); });
                break;
            /*
            ***************************************************************************************
            * ALBUM
            ***************************************************************************************
            */ 
            case FBMetodo.ALBUM_GET_FOTOS:

                modo = (_opt.parametros.modo ? _opt.parametros.modo : 'graph');
                sLimit = '';


                if (modo == 'graph') {

                    if (_opt.parametros != null && _opt.parametros.paginacion) {

                        if (_opt.parametros.paginacion.length == 1) {
                            sLimit = "limit=" + _opt.parametros.paginacion[0];
                        } else if (_opt.parametros.paginacion.length > 1) {
                            sLimit = "limit=" + _opt.parametros.paginacion[0] + "&offset=" + _opt.parametros.paginacion[1];
                        }

                    } else {
                        sLimit = "limit=1000000";
                    }


                    sQuery = '/' + (_opt.parametros != null && _opt.parametros.aid != null ? _opt.parametros.aid : WFB.getSession().id) + '/photos' + (sLimit.length > 0 ? '?' + sLimit : '');
                    debug('query:' + sQuery);



                    FB.api(sQuery, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });

                } else {

                    sQuery = "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.FOTO);
                    sQuery += " from photo where 1=1 ";

                    if (_opt.parametros != null && _opt.parametros.aid)
                        sQuery += ' and aid =\'' + _opt.parametros.aid + '\' ';
                    if (_opt.parametros != null && _opt.parametros.pid)
                        sQuery += ' and pid =\'' + _opt.parametros.pid + '\' ';

                    if (_opt.parametros != null && _opt.parametros.paginacion) {

                        if (_opt.parametros.paginacion.length == 1) {
                            sLimit = "limit " + _opt.parametros.paginacion[0];
                        } else if (_opt.parametros.paginacion.length > 1) {
                            sLimit = "limit " + _opt.parametros.paginacion[0] + " offset " + _opt.parametros.paginacion[1];
                        }

                    } else {
                        sLimit = "limit 1000000";
                    }
                    debug('query:' + sQuery + sLimit);

                    FB.api({
                        method: 'fql.query',
                        query: sQuery + sLimit
                    }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });


                }




                break;
            case FBMetodo.ALBUM_GET_INFO:
                sQuery = "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.ALBUM);
                sQuery += " from album where 1=1 ";

                if (_opt.parametros != null && _opt.parametros.aid)
                    sQuery += ' and aid =\'' + _opt.parametros.aid + '\'';
                if (_opt.parametros != null && _opt.parametros.uid)
                    sQuery += ' and owner =\'' + _opt.parametros.uid + '\'';


                debug('query:' + sQuery);

                FB.api({
                    method: 'fql.query',
                    query: sQuery
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            case FBMetodo.ALBUM_CREAR_NUEVO:

                FB.api(
                            '/' + (_opt.parametros != null && _opt.parametros.uid != null ? _opt.parametros.uid : WFB.getSession().id) + '/albums?access_token=' + WFB.getSession().token,
                            'post',
                            {
                                name: _opt.parametros.nombre,
                                message: (_opt.parametros != null && _opt.parametros.mensaje != null ? _opt.parametros.mensaje : '')
                            }
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            /*
            ***************************************************************************************
            * FOTO
            ***************************************************************************************
            */ 
            case FBMetodo.FOTO_GET_INFO:
                sQuery = "select " + (_opt.traeCampos() ? _opt.parametros.campos : FBCampos.FOTO);
                sQuery += " from photo where 1=1 ";

                if (_opt.parametros != null && _opt.parametros.aid)
                    sQuery += ' and aid =\'' + _opt.parametros.aid + '\'';
                if (_opt.parametros != null && _opt.parametros.pid)
                    sQuery += ' and pid =\'' + _opt.parametros.pid + '\'';


                debug('query:' + sQuery);

                FB.api({
                    method: 'fql.query',
                    query: sQuery
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });
                break;
            case FBMetodo.FOTO_SUBIR_FOTO:

                $.getJSON((_opt.parametros.url ? _opt.parametros.url : './js/modulos/dependencias/facebook/upload.php'), {
                    'access_token': WFB.getSession().token,
                    'imagen': _opt.parametros.imagen,
                    'mensaje': (_opt.parametros != null && _opt.parametros.mensaje) ? _opt.parametros.mensaje : '',
                    'aid': (_opt.parametros != null && _opt.parametros.aid) ? _opt.parametros.aid : 'me'
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });

                break;
            case FBMetodo.FOTO_ETIQUETAR:

                FB.api(
                            '/' + _opt.parametros.pid + '/tags?access_token=' + WFB.getSession().token,
                            'post',
                            { tags: _opt.parametros.tags }
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });

                break;
            /*
            ***************************************************************************************
            * PAGE
            ***************************************************************************************
            */ 
            case FBMetodo.PAGE_GET_FEED:
                debug('/' + _opt.parametros.id + '/feed?access_token=' + WFB.getSession().token);
                FB.api(
                                    '/' + _opt.parametros.id + '/feed?access_token=' + WFB.getSession().token
                        , function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response)); });
                break;
            case FBMetodo.PAGE_ES_FAN:

                FB.api({
                    method: 'pages.isFan',
                    page_id: _opt.parametros.page_id,
                    uid: (_opt.parametros != null && _opt.parametros.uid ? _opt.parametros.uid : WFB.getSession().id)
                }, function (response) { if (_opt.alFinalizar) _opt.alFinalizar(armarSalida(response, _opt.metodo)); });

                break;

            default:
                //alert('No mandaste nada.');/

                break;

        }




    }
}

function base64_url_decode ($input) {
    return base64_decode(strtr($input, '-_', '+/'));
} 

