UI= function()
{
    var _opcModalLoader = {
        texto   : "Espere un momento..."
    };
    var _opcBoton = {
        text : true,
        icon : {},
        label: null
    }
    return {
            alert: function(texto, opciones)
            {
                    var opt = jQuery.extend({	title: 'Atencion',
                                                                            width: '325px',
                                                                            modal: true,
                                                                            resizable: false,
                                                                            buttons: {"Aceptar": function() {
                                                                                            $(this).dialog("close");}
                                                                            },
                                                                            dialogClass: 'dialogo'
                                                                    }, opciones);

                    try
                    {
                            if(texto.substring(0, 3)!= "<p>")
                            {
                                    texto= JS.html.create('p', texto);
                            }
                    }
                    catch(err)
                    {
                            //[dami]esto parece una negrada pero funca de diez
                            texto= JS.html.create('p', texto);
                    }

                    if(opciones&& opciones.destino)
                    {
                            $(JS.html.create('div', texto)).dialog(opt).bind( "dialogclose", function(event, ui) {
                                            JS.redirect(opciones.destino);
                            });
                    }
                    else
                            {
                                    $(JS.html.create('div', texto)).dialog(opt);
                            }
            },
            estilarBotones: function (){
                var _icons = null;
                jQuery.each($('.ui-button'), function() {
                    
                    ui_icon = $(this).attr('ui-icon');
                    if ( ui_icon != "undefined" ){
                        _icons = {
                            primary : ui_icon
                        }
                    }
                        ui_icon = "ui-icon-" + ui_icon;

                    $(this).button({
                        icons: _icons,
                        text: true
                    });
                    
                });
            },
            menu: function (selector,options) {


                setTimeout(function (){$(selector).menu(options)},500);
                return;
            },
            chekbox: function (selector,options) {

//                if (!_includes["Checkbox"].cargado)
//                {
//                    JS.cargarDependencias(_includes["Checkbox"]);
//                }
//                setTimeout(function (){$(selector).checkbox(options)},500);
                return;
            },
            tooltip: function (selector,options) {

                var opt = jQuery.extend({
                                            track: true,
                                            delay: 0,
                                            fixPNG: true,
                                            showURL: false,
                                            showBody: " - ",
                                            top: -35,
                                            left: 5
                                        }, options);


//                if (!_includes["Tooltip"].cargado)
//                {
//                    JS.cargarDependencias(_includes["Tooltip"]);
//                }
                setTimeout(function (){$(selector).tooltip(opt)},500);
                return;
            },
           habilitarLink: function(sSearch, fnAccion){
                $(sSearch).removeClass('ui-state-disabled');
                $(sSearch).unbind("click");
                if(fnAccion)
                {
                    $(sSearch).click(fnAccion);
                }
            },
            deshabilitarLink: function(sSearch){
                $(sSearch).addClass('ui-state-disabled');
                $(sSearch).unbind("click");
                $(sSearch).click( function(e){
                    e.preventDefault();
                });
            },
            showModalLoader: function(opciones)
            {
                var _opt = jQuery.extend( _opcModalLoader, opciones);
                var _bar = "<div class=\"ui-div-img-modal-loader\"></div>";

                $('#ui-modal-loader').dialog('close');
                $('#ui-modal-loader').dialog('destroy');
                $('#ui-modal-loader').remove();

		$( "<div id=\"ui-modal-loader\"><span>" + _opt.texto + "</span>" + _bar + "</div>" ).dialog({
			height: 50,
                        width: 240,
			modal: true,
                        closeOnEscape: false,
                        dialogClass: 'no-close',
                        resizable : false
		});
                
                return false;
	
            },
            hideModalLoader: function()
            {
                if ( $('#ui-modal-loader').length > 0 ){
                    $('#ui-modal-loader').dialog('close');
                    $('#ui-modal-loader').dialog('destroy');
                    $('#ui-modal-loader').remove();
                    $('#ui-modal-loader').remove();
                }
            },
            ocultar: function(nodo)
            {
                $(nodo).addClass("ui-helper-hidden");
                $(".ui-helper-hidden").hide();
            },
            mostrar: function(nodo)
            {
                $(nodo).removeClass("ui-helper-hidden").show();
            },
            centrar: function(sSelector)
            {
                $(sSelector).css('position', 'absolute').css('top','50%').css('left','50%').css('margin-top','-'+($(sSelector).height()/2+ 'px')).css('margin-left','-'+($(sSelector).width()/2+ 'px')).parent().css('position', 'relative').css('text-align', 'center');
            },
            showPageLoader: function (pParam)
            {
                var opt = jQuery.extend({
                        top: 0,
                        left:0,
                        container: '#container'
                }, pParam);
                
                UI.hidePageLoader();
                
                $("<div class=\"modal-loading\"/>").appendTo($(opt.container));
                
            },
            hidePageLoader: function()
            {
                if ( $('.modal-loading').length > 0 ){
                    $('.modal-loading').remove();
                    $('.modal-loading').remove();
                }
            }
	
	}
}();