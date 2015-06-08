var _modulos = Array();
_modulos["UI"] 			= {dependencias:'ui.core.js',cargado:false};
_modulos["Form"]		= {dependencias:'jquery.form.js,jquery.validar.js',cargado:false};
_modulos["Encoding"]            = {dependencias:'',cargado:false};
_modulos["Path"]		= {dependencias:'',cargado:false};	
_modulos["Cookie"]		= {dependencias:'jquery.cookie.js',cargado:false};

JS= function()
{
    _carpeta : '';
	
    return {

		
		redirect: function(ruta, stayIfEmpty)
        {
	    if (ruta==null)
	        ruta = "";
	    if (ruta == "" && stayIfEmpty)
	        return;
            window.location= ruta;
        },
        setCookie: function(arrCookies, opciones)
        {
//            var options = { path: '/', expires: 10 };

            $(arrCookies).each(function(index){
                $.cookie(arrCookies[index].clave, arrCookies[index].valor);
            });
        },

        
        data:
        {
            str:
            {luky: function (array, separador){
                        var response = "";
                        var sep      = separador ? separador : ',';


                        $.each(array,
                                    function(i, l)
                                    {
                                        if (sep.indexOf("@") > -1)
                                            response += sep.replace('@',l);
                                        else
                                            response += l + sep;
                                    }
                        );
                        return response;
                    },
                    fromArray: function(arrEstringuis){
                        var sRetorno= '';

                        if(JS.data.isArray(arrEstringuis))
                        {
                            $.each(arrEstringuis, function(index){
                                sRetorno+= arrEstringuis[index]+ '\n';
                            });
                        }
                        else
                            {
                                sRetorno= arrEstringuis;
                            }

                        return sRetorno;
                    }
            },
            /* [dami] le queria poner boolean pero es una palabra reservada */
            bool:   {parse: function(sValor){
                        if(sValor=== 'true'|| sValor=== true|| parseInt(sValor)> 0)
                        {
                            return true;
                        }
                        else
                            {
                                return false;
                            }
                    }},
            isArray: function(valor)
            {
                if (valor.constructor.toString().indexOf("Array") == -1)
                {
                  return false;
                }
                else
                {
                  return true;
                }
            },
            vacio: function(dato)
            {
                return (dato=== "");
            },
            strPad: function (input, pad_length, pad_string, pad_type)
            {
                var half = '', pad_to_go;

                var str_pad_repeater = function (s, len) {
                    var collect = '', i;

                    while (collect.length < len) {collect += s;}
                    collect = collect.substr(0,len);

                    return collect;
                };

                input += '';
                pad_string = pad_string !== undefined ? pad_string : ' ';

                if (pad_type != 'STR_PAD_LEFT' && pad_type != 'STR_PAD_RIGHT' && pad_type != 'STR_PAD_BOTH') {pad_type = 'STR_PAD_RIGHT';}
                if ((pad_to_go = pad_length - input.length) > 0) {
                    if (pad_type == 'STR_PAD_LEFT') {input = str_pad_repeater(pad_string, pad_to_go) + input;}
                    else if (pad_type == 'STR_PAD_RIGHT') {input = input + str_pad_repeater(pad_string, pad_to_go);}
                    else if (pad_type == 'STR_PAD_BOTH') {
                        half = str_pad_repeater(pad_string, Math.ceil(pad_to_go/2));
                        input = half + input + half;
                        input = input.substr(0, pad_length);
                    }
                }
                return input;
            },
            recortar: function(sTexto, iLargo)
            {
                var sRetorno = sTexto;
                if (sTexto.length > iLargo)
                {
                    sRetorno = sTexto.substring(0, iLargo - 3) + "...";
                }
                return sRetorno;
            }
        },
        html:
        {
            create: function(sTag, valor)
            {
                var retorno = "";

                if(JS.data.isArray(valor))
                {
                    $.each(valor, function(i, l){
                        retorno += '<'+ sTag+'>' + l + '</'+ sTag+'>';
                    });
                }
                else
                    {
                        retorno += '<'+ sTag+'>' + valor + '</'+ sTag+'>';
                    }

                return retorno;
            },
            incluir: function (filename, filetype){
             
                 var fileref;
                 if (filetype=="js"){ //if filename is a external JavaScript file
                  fileref=document.createElement('script')
                  fileref.setAttribute("type","text/javascript")
                  fileref.setAttribute("src", filename)
                 }
                 else if (filetype=="css"){ //if filename is an external CSS file
                  fileref = document.createElement("link")
                  fileref.setAttribute("rel", "stylesheet")
                  fileref.setAttribute("type", "text/css")
                  fileref.setAttribute("href", filename)
                 }
                 if (typeof fileref!="undefined")
                  document.getElementsByTagName("head")[0].appendChild(fileref)
            }
        },

        ajax:{
                llamada: function ( opciones ){
                    var _complete = false;
                    var opt = jQuery.extend({
                                                url: '',
                                                timeout : 5000,
                                                data: null,
                                                dataType: 'json',
                                                success: function ( json ){

                                                    console.log('---->success')
                                                    _complete = true;
                                                    if ( opt.alFinalizar )
                                                        opt.alFinalizar (json);
                                                },
                                                error : function (jqXHR, textStatus, errorThrown){
                                                        console.log('---->error')
                                                        _complete = true;
                                                        if ( opt.alFinalizar ){
                                                            var json_resp = {
                                                                state : -1,
                                                                message : textStatus,
                                                                error : {
                                                                    code : textStatus,
                                                                    msg  : errorThrown,
                                                                    extra: jqXHR
                                                                }
                                                            }
                                                            opt.alFinalizar (json_resp);
                                                        }

                                                }

                                            }, opciones);


                    $.ajax( opt );
                },
                cargarContenido: function ( opciones )
                {
                    $.ajax({
                       url: opciones.ruta,
                       data: (opciones.datos ? opciones.datos : null),
                       success: function (html){
                           $('#' + opciones.contenedor).html(html);
                           if (opciones.alFinalizar)
                                   opciones.alFinalizar();
                       }
                     });
                },
                getJSON: function ( url, opciones ){

                    $.ajax({
                       url: url,
                       dataType: 'json',
                       data: (opciones.datos ? opciones.datos : null),
                       //async: (opciones.async ? opciones.async : false),
                       success: function (json){
                           if (opciones.alFinalizar)
                                   opciones.alFinalizar(json);
                       }
                     });

                }
            }


    };
}();
//***********************************************************************************************************//
if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, ''); 
  }
}
if(!("console" in window) || !("firebug" in console)) {
    var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml", "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"];

    window.console = {};

    for(var i = 0; i < names.length; ++i) window.console[names[i]] = function() {};
}

function log(msg) {
        if(console)
		console.log(msg);
}

function inspect(obj) {
	if(console)
		console.dir(obj);
}

function debug(obj) {
	if(console)
		console.debug(obj);
}

