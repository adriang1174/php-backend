(function($) {
    $.facebook = function(options){

        opt = $.extend( {}, $.facebook.defaults, options );
        
        if (String(opt.app_id).search(/^\s*(\+|-)?\d+\s*$/) != -1){
            $.ajaxSetup({ cache: true });
            $.getScript('//connect.facebook.net/'+ opt.lang +'/sdk.js', function(){
                FB.init({
                    appId      : opt.app_id,
                    status     : true,
                    xfbml      : true,
                    channelUrl : opt.channel,
                    version    : 'v2.0'
                });     
                
                if (typeof FB == 'object' && opt.app_id != null && typeof opt.onLoad === 'function'){
                    opt.onLoad();
                }

            });        

        }


    };
    $.facebook.getLoginStatus = function(options){
        opt = $.extend( {
            login:{},
            onSuccess: function(r){},
            onCancel: function(r){}
        },$.facebook.defaults, options );
        
        if (opt.login.auto === undefined){
            opt.login.auto = true;
        }
        if (opt.login.type === undefined){
            opt.login.type = 'page';
        }
        
        debug(opt);
        log('$.facebook.getLoginStatus');
        
        FB.getLoginStatus(function (response) {
            if (response.status !== 'connected'){
                if (opt.login && opt.login.auto === true){
                    $.facebook.login(options);
                }else{
                    
                    if (typeof opt.onCancel === 'function'){
                        opt.onCancel(response.status);
                    }                       
                }
            }else{
                $.facebook.manageSession(response);
                if (typeof opt.onSuccess === 'function'){
                    opt.onSuccess(response.authResponse);
                }                
            }
        });        
        
    };
    $.facebook.login = function(options){
        opt = $.extend( {
            login:{},
            onSuccess: function(r){},
            onCancel: function(r){}
        },true,$.facebook.defaults, options );
        
        
        
        if (opt.type === undefined){
            opt.type = 'page';
        }        
        

        
        switch(opt.type){
            case 'canvas':
                var oauth_url = "http://www.facebook.com/dialog/oauth/?"
                                + "client_id=" + opt.app_id
                                + "&redirect_uri=" + (opt.redirect_uri ? opt.redirect_uri : '')
                                + "&scope=" + (opt.scope ? opt.scope : null);
                window.top.location.href = oauth_url;
                break;
            case 'mobile':
                var oauth_url = "http://www.facebook.com/dialog/oauth/?"
                                + "client_id=" + opt.app_id
                                + "&redirect_uri=" + (opt.redirect_uri ? opt.redirect_uri : '')
                                + "&scope=" + (opt.scope ? opt.scope : null);
                window.location.href = oauth_url;
                break;
            case 'page':
            default:
                _opt_login = {
                    scope: (opt.scope ? opt.scope : null),
                    return_scopes: (opt.return_scopes !== undefined ? opt.return_scopes : true)
                    
                }
                
                if (opt.auth_type !== undefined){
                    _opt_login.auth_type = opt.auth_type;
                }
                
                if (opt.type === 'page'){
                    //_opt_login.display = 'popup'
                }
                
                FB.login(function (response) {
                    $.facebook.manageSession(response);
                    if (response.status === 'connected'){
                        if (typeof opt.onSuccess === 'function'){
                            opt.onSuccess(response.authResponse);
                        }                    
                    }else{
                        if (typeof opt.onCancel === 'function'){
                            opt.onCancel(response.status);
                        }                    
                    }

                }, _opt_login);   
                break;
        }
    };
    
    $.facebook.setSession = function(data){
        debug('->$.facebook.setSession');
        _facebookSession = jQuery.extend(true,_facebookSession, data);        
    };
    
    $.facebook.getSession = function(){
        return _facebookSession;
    };
    
    $.facebook.manageSession = function (response) {
        debug('->$.facebook.manageSession');
        
        if (response.authResponse != null) {
            
            $.facebook.setSession({
                uid: response.authResponse.userID,
                signedRequest: response.authResponse.signedRequest,
                token: response.authResponse.accessToken,
                status: response.status,
                scopes: (response.perms != null) ? response.perms : null
            });
        } else {
            $.facebook.setSession({
                status: response.status
            });
        }
    }

    $.facebook.api = function(options){
        
        opt = $.extend( {
            path: 'me',
            method: 'get',
            params: {},
            onSuccess: function(r){},
            onError: function(r){}
        },$.facebook.defaults, options );        

        

        switch(opt.path){
            
            case 'me':
            case 'me/permissions':
            case 'me/friends':
                
                FB.api('/'+opt.path,opt.method,opt.params,function(rApi){
                    if (rApi.error === undefined){
                        if (typeof opt.onSuccess === 'function'){
                            opt.onSuccess(rApi);
                        }   
                    }else{
                        if (typeof opt.onError === 'function'){
                            opt.onError(rApi);
                        }                        
                    }
                })
                break;
            
        }
        
        


    }
    $.facebook.getPermissions = function(options){
        alert('getPermission');
        opt = $.extend( {
            path: 'me/permissions',
            onSuccess: function(r){},
            onError: function(r){}
        }, options );    
        
        $.facebook.api(opt);
    }
    
    $.facebook.getLongToken = function(options){
        opt = $.extend(true,{
            onSuccess: function(r){},
            onCancel: function(r){}
        },$.facebook.defaults, options );
        
        JS.ajax.llamada({
            url: 'js/modulos/dependencias/facebook/getlonaccesstoken.php',
            data:{
                access_token: $.facebook.getSession().token
            },
            alFinalizar:function(r){
                if (r.state == 1){
                    $.facebook.setSession({token:r.data.access_token});
                    if (typeof opt.onSuccess === 'function'){
                        opt.onSuccess(r);
                    }                        
                }else{
                    if (typeof opt.onCancel === 'function'){
                        opt.onCancel(r);
                    }                        
                }
                
            }
        });          
        
        
        
    };

    $.facebook.defaults = {
        app_id: null,
        channel: null,
        scope: '',
        lang: 'en_US',
        onLoad: function(){}
        
    };
    
    var _facebookSession = {
        uid:null,
        token:null,
        signedRequest:null,
        status:'',
        scopes: null
    }
    
    function debug($obj) {
      if (window.console && window.console.debug)
        window.console.debug($obj);
    };
    function log($a) {
      if (window.console && window.console.log)
        window.console.log($a);
    };



    


})(jQuery);