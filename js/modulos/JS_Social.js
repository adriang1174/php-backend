Social= function()
{
	return {

                facebook: {

                    shareDialog : function (options) {
                                    var bButtonEnabled = true;
                                    var opt = $.extend({
                                        title: 'Publicar en el muro',
                                        name : '',
                                        caption: '',
                                        message: '',
                                        description: '',
                                        image: '',
                                        container: 'body',
                                        width: '500',
                                        height: '160',
                                        url: '',
                                        link:'',
                                        data: {},
                                        publish: function (r) {},
                                        cancel: function () {}

                                    }, options);


                                    center_x = ($(opt.container).width()/2)  - (opt.width/2);
                                    center_y = ($(opt.container).height()/2) - (opt.height/2);

                                    
                                    $(opt.container).append('<div id="fbShareDialog"><div id="fbShare" style="position: absolute;top: 0;width: '+$(opt.container).width()+'px;height:'+ $(opt.container).height() +'px;position:absolute;z-index:9999;">'+
                                                '<div id="fbShareBoxContainer" style="background-color: #FFFFFF;border: 1px solid #003366;height: 160px;left: '+ center_x +'px;position: absolute;top: '+ center_y +'px;width: 500px;z-index: 100;">'+
                                                '<div id="fbShareTitle" style="background-color:#3b5998;color:#FFF;font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold;padding:5px;">'+ opt.title +'</div>'+
                                                '<div id="fbShareImage" style="border:1px solid #999;width:80px;height:80px;left:20px;top:40px;position:absolute;"><img src="'+ opt.image +'" width="80" height="80">'+
                                              '</div>'+
                                                '<div id="fbShareEnviando" style="display:none;background-color: #fff;color: #333333;font-family: Arial,Helvetica,sans-serif;font-size: 12px;height: 120px;left: 111px;position: absolute;top: 37px;width: 382px;z-index:999;background-image:url(\'/frm/Social/Facebook/img/sending.gif\');background-repeat:no-repeat;background-position:center;text-align:center;">Estamos enviando las publicaciones...</div>'+
                                                '<div id="fbShareDescription" style="width:290px;height:80px;left:130px;top:40px;position:absolute;font-family:Arial, Helvetica, sans-serif;font-size:12px;color:#333;">'+ opt.description +'</div>'+
                                                    '<a id="fbShareEnviar" href="javascript:void(0);" style="background-color: #627aad;    color: #FFFFFF;    font-family: Arial,Helvetica,sans-serif;    font-size: 11px;    left: 130px;    padding: 4px;    position: absolute;    top: 128px;    width: 80px;	text-align:center;	text-transform:uppercase;	display:block;	text-decoration:none;	border:1px solid #1d4088;">Publicar</a>'+
                                                    '<a id="fbShareCancelar" href="javascript:void(0);" style="background-color: #627aad;color: #FFFFFF;font-family: Arial,Helvetica,sans-serif;font-size: 11px;left: 244px;padding: 4px;position: absolute;top: 128px;width: 80px;text-align:center;text-transform:uppercase;display:block;text-decoration:none;border:1px solid #1d4088;">Cancelar</a>'+
                                            '</div>'+
                                            '<div id="fbShareBack" style="background-color:#fff;filter: alpha( opacity = 80 );opacity: .8;position: absolute;top: 0;width: '+$(document).width()+'px;height:'+ $(document).height() +'px;position:absolute;z-index:1;	">'+
                                            '</div>'+
                                        '</div></div>');

                                    $("#fbShareDialog #fbShareEnviar").click(function (){
                                        if (bButtonEnabled)
                                        {
                                            bButtonEnabled = false;
                                            if (opt.url != '')
                                            {
                                                $('#fbShareEnviando').show();

                                                data = (opt.data) ? opt.data : {};
                                                data.name = opt.name;
                                                data.description = opt.description;
                                                data.image = opt.image;
                                                data.link = opt.link;
                                                data.caption = opt.caption;
                                                data.message = opt.message;

                                                $.ajax({
                                                           url      :   opt.url,
                                                           data     :   data,
                                                           dataType :   'json',
                                                           async    :   false,
                                                           success  :   function (resp){
                                                                            bButtonEnabled = true;
                                                                            opt.publish (resp);
                                                                            $('#fbShareDialog').remove();
                                                                        }
                                                });

                                            }else
                                            {
                                                bButtonEnabled = true;
                                                opt.publish ();
                                                $('#fbShareDialog').remove();
                                            }


                                        }
                                        
                                    });
                                    $("#fbShareDialog #fbShareCancelar").click(function (){
                                        opt.cancel();
                                        $('#fbShareDialog').remove();
                                    });
                            }


                    

                }


	}
}();