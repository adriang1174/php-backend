CarritoCompra = function()
{
    var _yaInicializo = false;
    var _opciones = {
        datos       : [],

        imgGenericaListado: 'img_generica.jpg',
        imgGenericaDetalle: 'img_generica_detalle.jpg',
        nombreImgBotCont: 'btn_continue_shopping.png',
        nombreImgBotDet: 'btn_view_cart.png',
        rutaImagenes: 'images/',
        tipo        : 'P',

//        productos   : {
//            url         : null,
//            contenedor  : 'divProductos'
//        },
//        carrito     : {
//            url         : null,
//            contenedor  : 'divCarrito'
//        },

        params      : {},
        pagina      : 1,
        cantRegPag  : 10,
        paginador   : false,

        urlCarrito  : null,
        urlDetalleCarrito       : null,
        urlProductos: null,
        urlDetalle  : 'productos_detalle.php',

        idDivCarrito    : 'divCarrito',
        idDivProductos  : 'divProductos',
        idDivDetalleProducto    : 'divDetalle',


        classDivItemCarrito     : 'itemCarrito',
        classDivItemProductos   : 'itemProducto',

        classAgregarProducto    : 'add-button',
        classIncrementar        : 'add-plus',
        classDecrementar        : 'add-minus',

        classEliminarProducto   : 'del-button',
        idConfirmarPedido       : 'confirm-carrito',

        
        alAgregarProducto       : null,
        alModificarProducto     : null,
        alQuitarProducto        : null,
        alVaciarCarrito         : null,
        alListarCarrito         : null,
        alListarProductos       : null,
        alObtenerDetalleProducto: null,
        alConfirmarPedido       : null,
        alConfirmarPago         : null,
        mostrarAlerts           : true

    };
    
    return{
        inicializar: function ( opc ){

            if (!_yaInicializo){

                _opciones = $.extend(_opciones,opc);

                $('body').on("click","#aw_acp_continue",function(){
                   $('.ajaxcartpro_confirm').remove();
                });

                $('#' + _opciones.idDivProductos ).on( "click", "." + _opciones.classIncrementar, function() {

                    _codigo = $(this).attr('codigo');
                    _cant = $('#' + _opciones.idDivProductos + ' #cantidad_' + _codigo).val();

                    $('#' + _opciones.idDivProductos + ' #cantidad_' + _codigo).val( parseInt(_cant) + 1 );

                }).on( "click", "." + _opciones.classDecrementar, function() {

                    _codigo = $(this).attr('codigo');
                    _cant = $('#' + _opciones.idDivProductos + ' #cantidad_' + _codigo).val();

                    if (_cant > 1)
                        $('#' + _opciones.idDivProductos + ' #cantidad_' + _codigo).val( parseInt(_cant) - 1 );

                }).on( "blur", "input[name=cantidad]", function() {

                    if ( /\D/.test( $(this).val() ) ){
                        $(this).val(1);
                    }

                }).on( "click", "." + _opciones.classAgregarProducto , function() {

                    _codigo = $(this).attr('codigo');
                    _nombre = $(this).attr('nombre');
                    _cant   = $('#' + _opciones.idDivProductos + ' #cantidad_' + _codigo).val();
                    
                    CarritoCompra.carrito.agregarProducto({
                       params:{
                            codigo : _codigo,
                            nombre : _nombre,
                            cantidad : _cant
                       }
                    });

                });
                
                $('#' + _opciones.idDivDetalleProducto ).on( "click", "." + _opciones.classIncrementar, function() {

                    _codigo = $(this).attr('codigo');
                    _cant = $('#' + _opciones.idDivDetalleProducto + ' #cantidad_' + _codigo).val();

                    $('#' + _opciones.idDivDetalleProducto + ' #cantidad_' + _codigo).val( parseInt(_cant) + 1 );

                }).on( "click", "." + _opciones.classDecrementar, function() {

                    _codigo = $(this).attr('codigo');
                    _cant = $('#' + _opciones.idDivDetalleProducto + ' #cantidad_' + _codigo).val();

                    if (_cant > 1)
                        $('#' + _opciones.idDivDetalleProducto + ' #cantidad_' + _codigo).val( parseInt(_cant) - 1 );

                }).on( "blur", "input[name=cantidad]", function() {

                    if ( /\D/.test( $(this).val() ) ){
                        $(this).val(1);
                    }

                }).on( "click", "." + _opciones.classAgregarProducto , function() {

                    _codigo = $(this).attr('codigo');
                    _nombre = $(this).attr('nombre');
                    _cant   = $('#' + _opciones.idDivDetalleProducto + ' #cantidad_' + _codigo).val();
                    
                    CarritoCompra.carrito.agregarProducto({
                       params:{
                            codigo : _codigo,
                            nombre : _nombre,
                            cantidad : _cant
                       }
                    });

                });
                $('#' + _opciones.idDivCarrito ).on( "blur", "input[name=cantidad]", function() {

                    if ( /\D/.test( $(this).val() ) ){
                        $(this).val(1);
                    }

                    _codigo = $(this).attr('codigo');
                    _cant   = $(this).val();

                    CarritoCompra.carrito.modificarProducto({
                       params:{
                            codigo : _codigo,
                            cantidad : _cant
                       }
                    });



                }).on( "click", "." + _opciones.classEliminarProducto, function() {

                    _codigo = $(this).attr('codigo');
                    CarritoCompra.carrito.eliminarProducto({
                       params:{
                            codigo : _codigo
                       }
                    });
                });

                $('body').on( "click", "#" + _opciones.idConfirmarPedido, function() {

                    if (confirm('Está seguro que desea confirmar su pedido?')){
                        CarritoCompra.carrito.confirmar();
                    }
                });

            }
            _yaInicializo = true;


        },
        ui: {

            mostrarBarraProgreso : function ( mensaje ){
                mensaje = (mensaje != null && mensaje != undefined ? mensaje : 'Cargando...');

                if ( $('.ajaxcartpro_progress').length > 0 )
                        $('.ajaxcartpro_progress').remove();
                $("body").append('<div style="width: 260px; height: 50px; left: 542px; top: 175px;" class="ajaxcartpro_progress"><img alt="" src="images/al.gif"><p>' + mensaje + '</p></div>');
            },
            ocultarBarraProgreso : function (){
                $('.ajaxcartpro_progress').remove();
            },
            mostrarMsjProdAgregado: function ( mensaje, urlDetalleCarrito ){

                    mensaje = (mensaje != null && mensaje != undefined ? mensaje : 'Agregado al carrito');
                    

                    if ( $('.ajaxcartpro_confirm').length > 0 )
                        $('.ajaxcartpro_confirm').remove();

                    _divFin = '<div style="display: block; width: 260px; height: 104px; left: 542px; top: 163px;" class="ajaxcartpro_confirm"><p><span id="acp_product_name"></span>' + mensaje + '</p><a class="focus" id="aw_acp_continue" style="background-attachment: scroll;background-clip: border-box;background-color: transparent;background-image: url(\'' + _opciones.rutaImagenes + _opciones.nombreImgBotCont + '\');background-origin: padding-box; background-position: 0 0; background-repeat: repeat;background-size: auto auto;color: #333333;width: 144px;" href="javascript:void(0);">Continuar</a>';

                    if (urlDetalleCarrito != null){
                        _divFin += '<a style="background-attachment: scroll; background-clip: border-box;background-color: transparent;background-image: url("' + _opciones.rutaImagenes +  _opciones.nombreImgBotDet + '");background-origin: padding-box;background-position: 0 0;background-repeat: repeat;background-size: auto auto;color: #FFFFFF;" href="' + urlDetalleCarrito  + '" id="aw_acp_checkout">Ver carro &amp; finalizar</a>';
                    }

                    _divFin += '</div>';

                    $("body").append(_divFin);

            },
            ocultarMsjProdAgregado: function (){
                $('.ajaxcartpro_confirm').remove();
            },
            mostrarMsj: function ( mensaje, urlDestino ){

                    mensaje = (mensaje != null && mensaje != undefined ? mensaje : 'Operación exitosa.');


                    if ( $('.ajaxcartpro_confirm').length > 0 )
                        $('.ajaxcartpro_confirm').remove();

                    _divFin = '<div style="display: block; width: 260px; height: 104px; left: 542px; top: 163px;" class="ajaxcartpro_confirm"><p><span id="acp_product_name"></span>' + mensaje + '</p><a class="focus" id="aw_acp_continue" style="background-attachment: scroll;background-clip: border-box;background-color: transparent;background-image: url(\'' + _opciones.rutaImagenes + _opciones.nombreImgBotCont + '\');background-origin: padding-box; background-position: 0 0; background-repeat: repeat;background-size: auto auto;color: #333333;width: 144px;" href="' + (urlDestino != null ? urlDestino : 'javascript:void(0);' ) + '">Continuar</a>';
                    _divFin += '</div>';

                    $("body").append(_divFin);

            },
            ocultarMsj: function (){
                $('.ajaxcartpro_confirm').remove();
            }
        },

        productos: {

            
            listar: function(opc){

                var _opc = $.extend(_opciones,opc);
                $('#' + _opc.idDivProductos ).empty();

                CarritoCompra.ui.mostrarBarraProgreso();

                _opc.url                = _opc.urlProductos;
                _opc.accion             = 'list';
                _opc.tipo               = 'P';
                
                _opc.params.accion      = _opc.accion;
                _opc.params.pagina      = _opc.pagina;
                _opc.params.cantRegPag  = _opc.cantRegPag;

                CarritoCompra.ejecutarAccion(_opc);

            },
            verDetalle : function (opc){
                var _opc = $.extend(_opciones,opc);
                
                $('#' + _opc.idDivDetalleProducto ).empty();

                $("body").append('<div style="width: 260px; height: 50px; left: 542px; top: 175px;" class="ajaxcartpro_progress"><img alt="" src="images/al.gif"><p>Cargando, espere...</p></div>');

                _opc.url                = _opc.urlProductos;
                _opc.accion             = 'detalle';
                _opc.tipo               = 'P';

                _opc.params.accion      = _opc.accion;

                if ( _opc.params.id == undefined && _opc.params.codigo == undefined ){
                    alert('Falta especificar identificador de producto');
                    return false;
                }

                CarritoCompra.ejecutarAccion(_opc);

            }

        },
        carrito: {

            agregarProducto: function (opc){

                var _opc = $.extend(_opciones,opc);
                
                CarritoCompra.ui.mostrarBarraProgreso('Agregando producto...');

                _opc.url                = _opc.urlCarrito;
                _opc.accion             = 'add';
                _opc.tipo               = 'C';

                _opc.params.accion      = _opc.accion;

                if ($.trim(_opc.params.cantidad) < 1){
                    CarritoCompra.ui.ocultarBarraProgreso();
                    alert("Debe ingresar una cantidad mayor a 0.");
                    return false;
                }

                CarritoCompra.ejecutarAccion(_opc);
                

            },
            listar: function(opc){

                var _opc = $.extend(_opciones,opc);
                $('#' + _opc.idDivCarrito ).empty();

                CarritoCompra.ui.mostrarBarraProgreso();

                _opc.url                = _opc.urlCarrito;
                _opc.accion             = 'list';
                _opc.tipo               = 'C';

                _opc.params.accion      = _opc.accion;
                _opc.params.pagina      = _opc.pagina;
                _opc.params.cantRegPag  = _opc.cantRegPag;

                CarritoCompra.ejecutarAccion(_opc);

            },
            modificarProducto: function (opc){

                var _opc = $.extend(_opciones,opc);

                CarritoCompra.ui.mostrarBarraProgreso('Modificando producto...');

                _opc.url                = _opc.urlCarrito;
                _opc.accion             = 'upd';
                _opc.tipo               = 'C';

                _opc.params.accion      = _opc.accion;

                if ($.trim(_opc.params.cantidad) < 1){
                    CarritoCompra.ui.ocultarBarraProgreso();
                    alert("Debe ingresar una cantidad mayor a 0.");
                    return false;
                }

                CarritoCompra.ejecutarAccion(_opc);

            },
            eliminarProducto: function (opc){

                var _opc = $.extend(_opciones,opc);

                CarritoCompra.ui.mostrarBarraProgreso('Eliminando producto...');

                _opc.url                = _opc.urlCarrito;
                _opc.accion             = 'del';
                _opc.tipo               = 'C';

                _opc.params.accion      = _opc.accion;

                CarritoCompra.ejecutarAccion(_opc);

            },
            vaciar: function (opc){

                var _opc = $.extend(_opciones,opc);

                _opc.url                = _opc.urlCarrito;
                _opc.accion             = 'clean';
                _opc.tipo               = 'C';

                _opc.params.accion      = _opc.accion;

                CarritoCompra.ejecutarAccion(_opc);

            },
            confirmar: function (opc){
                var _opc = $.extend(_opciones,opc);

                CarritoCompra.ui.mostrarBarraProgreso('Espere mientras se procesa el pedido...');

                _opc.url                = _opc.urlCarrito;
                _opc.accion             = 'confirm';
                _opc.tipo               = 'C';

                _opc.params.accion      = _opc.accion;

                CarritoCompra.ejecutarAccion(_opc);
            },
            confirmarPago: function (opc){
                var _opc = $.extend(_opciones,opc);

                CarritoCompra.ui.mostrarBarraProgreso('Espere mientras se procesa el pago...');

                _opc.url                = _opc.urlCarrito;
                _opc.accion             = 'confirm_mp';
                _opc.tipo               = 'C';

                _opc.params.accion      = _opc.accion;

                CarritoCompra.ejecutarAccion(_opc);
            }


        },



        ejecutarAccion: function (opc){

            

            JS.ajax.getJSON(opc.url,{
                datos: opc.params,
                alFinalizar: function (json){
                    CarritoCompra.ui.ocultarBarraProgreso();
                    _linea = "";
                    switch(opc.accion){

                        case 'list':
                                if ( opc.tipo == 'P' ){

                                    

                                    for(linea in json.data.registros){

                                        _imagen = (json.data.registros[linea].Imagen1 ? opc.rutaImagenes + json.data.registros[linea].Imagen1 : opc.imgGenericaListado);

                                        _linea = '  <div class="' + opc.classDivItemProductos + '">'
                                                        + '<a href="' + (opc.urlDetalle != null ? opc.urlDetalle + '?codigo=' + json.data.registros[linea].Codigo: '#' ) + '"><img src="' + _imagen + '" alt=" " class="imagen" style="width:100px;height:100px;" /></a><br />'
                                                    + '<a href="' + (opc.urlDetalle != null ? opc.urlDetalle + '?codigo=' + json.data.registros[linea].Codigo: '#' ) + '">' + JS.data.recortar(json.data.registros[linea].Nombre,25) + '</a><br />'
                                                        + '<span class="precio">$ ' + json.data.registros[linea].Precio + '</span><br />'
                                                        + '<div class="actions" style="padding-left:27px;"><div class="list-col-2">'
                                                        + '<div class="add-combo">'
                                                        + '<a class="'+ opc.classIncrementar+'" codigo="' + json.data.registros[linea].Codigo + '"></a>'
                                                        + '<a class="'+ opc.classDecrementar+'" codigo="' + json.data.registros[linea].Codigo + '"></a>'
                                                        + '</div>'
                                                            + '<input type="text"  value="1" maxlength="12" id="cantidad_' + json.data.registros[linea].Codigo + '" class="input-text qty" name="cantidad"/>'
                                                            + '<a class="'+ opc.classAgregarProducto+'" href="javascript:void(0);" codigo="' + json.data.registros[linea].Codigo + '" nombre="' + json.data.registros[linea].Nombre + '"></a>'
                                                        + '</div></div>'
                                                    + '</div>';




                                        $('#' + opc.idDivProductos ).append( _linea );
                                    }


                                    $('#' + opc.idDivProductos + ' input[name=cantidad]').val(1);
                                    Form.soloNumeros($('#' + opc.idDivProductos + ' input[name=cantidad]'));

                                    if ( opc.alListarProductos )
                                        opc.alListarProductos( json );

                            } else {
                                $('#' + opc.idDivCarrito ).empty();
                                _subtotal = 0;
                                _cabecera = '<div class="titulo">'
                                              +'<table cellspacing="0" cellpadding="0" class="tabla">'
                                              +'  <tr>'
                                              +'    <td class="foto">&nbsp;</td>'
                                              +'    <td align="left" valign="middle" class="detalle">Detalle</td>'
                                              +'    <td align="center" valign="middle" class="precio">Precio x u.</td>'
                                              +'    <td align="center" valign="middle" class="cantidad">Cantidad</td>'
                                              +'    <td align="center" valign="middle" class="subtotal">Subtotal</td>'
                                              +'    <td align="center" valign="middle" class="opciones">&nbsp;</td>'
                                              +'  </tr>'
                                              +'</table>'
                                            +'</div>';
                                    $('#' + opc.idDivCarrito ).append( _cabecera );

                                    if ( json.data.total == 0){
                                        $('#' + opc.idConfirmarPedido).hide();
                                    }else{
                                        $('#' + opc.idConfirmarPedido).show();
                                    }

                                    for(linea in json.data.registros){
                                        
                                        _imagen = (json.data.registros[linea].imagen ? opc.rutaImagenes + json.data.registros[linea].imagen : opc.imgGenericaListado);

                                        _linea = '    <div class="' + opc.classDivItemCarrito + '">'
                                                      +'<table cellspacing="0" cellpadding="0" class="tabla">'
                                                      +'  <tr>'
                                                      +'    <td align="center" valign="middle" class="foto"><a href="' + (opc.urlDetalle != null ? opc.urlDetalle + '?codigo=' + json.data.registros[linea].codigo: '#' ) + '"><img src="' + _imagen + '" alt=" " class="imagen" /></a></td>'
                                                      +'    <td align="left" valign="middle" class="detalle"><span class="NombreProd"><a href="' + (opc.urlDetalle != null ? opc.urlDetalle + '?codigo=' + json.data.registros[linea].codigo: '#' ) + '">' + json.data.registros[linea].nombre + '</a></span><br />'
//                                                      +'      Detalle del producto - Detalle del producto<br />'
//                                                      +'      Detalle del producto - Detalle del producto </td>'
                                                      +'    <td align="center" valign="middle" class="precio">$'+ json.data.registros[linea].precio +'</td>'
                                                      +'    <td align="center" valign="middle" class="cantidad"><label for="textfield"></label>'
                                                      +'        <input type="text"  value="' + json.data.registros[linea].cantidad + '" maxlength="12" codigo="' + json.data.registros[linea].codigo + '" id="cantidad_' + json.data.registros[linea].codigo + '" class="input-text qty" name="cantidad"/></td>'
                                                      +'    <td align="center" valign="middle" class="subtotal">$'+ json.data.registros[linea].subtotal +'</td>'
                                                      +'    <td align="center" valign="middle" class="opciones"><a href="javascript:void(0);" class="'+ opc.classEliminarProducto+'" codigo="' + json.data.registros[linea].codigo + '"><img src="images/btn_eliminar.jpg" alt="Eliminar Producto" width="24" height="23" border="0" /></a></td>'
                                                      +'  </tr>'
                                                      +'</table>'
                                                    +'</div>';

                                        //_subtotal += json.data.registros[linea].subtotal;



                                        $('#' + opc.idDivCarrito ).append( _linea );
                                    }
                                _total = '    <div class="total">'
                                              +'<table cellspacing="0" cellpadding="0" class="tabla">'
                                              +'  <tr>'
                                              +'    <td class="foto">&nbsp;</td>'
                                              +'    <td align="left" valign="middle" class="detalle">&nbsp;</td>'
                                              +'    <td align="center" valign="middle" class="precio">&nbsp;</td>'
                                              +'    <td align="center" valign="middle" class="cantidad">Total</td>'
                                              +'    <td align="center" valign="middle" class="subtotal">$' + json.data.subtotal + '</td>'
                                              +'    <td align="center" valign="middle" class="opciones">&nbsp;</td>'
                                              +'  </tr>'
                                              +'</table>'
                                            +'</div>';
                                $('#' + opc.idDivCarrito ).append( _total );

                                //
                                //$('#' + opc.idDivCarrito ).append( _linea );
                                //$('#' + opc.idDivCarrito + ' input[name=cantidad]').val(1);
                                Form.soloNumeros($('#' + opc.idDivCarrito + ' input[name=cantidad]'));

                            }

                            break;
                        case 'add':
                                    
                                    if ( json.state == 1  && opc.mostrarAlerts == true){
                                        CarritoCompra.ui.mostrarMsjProdAgregado('Producto agregado.',opc.urlDetalleCarrito);

                                    }

                                    if ( opc.alAgregarProducto )
                                        opc.alAgregarProducto( json );

                            break;
                        case 'upd':

                                    if ( opc.alModificarProducto )
                                        opc.alModificarProducto( json );

                            break;
                        case 'del':

                                    if ( opc.alQuitarProducto )
                                        opc.alQuitarProducto( json );


                            break;
                        case 'clean':

                                    if ( opc.alVaciarCarrito )
                                        opc.alVaciarCarrito( json );


                            break;
                        case 'detalle':

                                if ( opc.tipo == 'P' ){

                                    _imagen = (json.data.imagen ? opc.rutaImagenes + json.data.imagen : opc.imgGenericaDetalle);


                                    _linea = '      <div class="image">'
                                                + '     <img src="' + _imagen + '" alt=" " />'
                                                + ' </div><br />'
                                                + ' <div class="txt"><span class="tit">' + json.data.nombre + '</span><br />'
                                                + ' <span class="precio">$' +  json.data.precio + '</span><br />'
                                                        + '<div class="list-col-2">'
                                                        + '<div class="add-combo">'
                                                        + '<a class="'+ opc.classIncrementar+'" codigo="' + json.data.codigo + '"></a>'
                                                        + '<a class="'+ opc.classDecrementar+'" codigo="' + json.data.codigo + '"></a>'
                                                        + '</div>'
                                                            + '<input type="text"  value="1" maxlength="12" id="cantidad_' + json.data.codigo + '" class="input-text qty" name="cantidad"/>'
                                                            + '<a class="'+ opc.classAgregarProducto+'" href="javascript:void(0);" codigo="' + json.data.codigo + '" nombre="' + json.data.nombre + '"></a>'
                                                        + '</div>'
                                                
                                                + ' <div class="descrip">' + (json.data.descripcion != null ? json.data.descripcion : '') + '</div><br/>'
                                                + ' <span class="cod">Código: ' + json.data.codigo + '</span></div>';




                                    $('#' + opc.idDivDetalleProducto ).append( _linea );



                                    $('#' + opc.idDivDetalleProducto + ' input[name=cantidad]').val(1);
                                    Form.soloNumeros($('#' + opc.idDivDetalleProducto + ' input[name=cantidad]'));
                                    
                                    if ( opc.alObtenerDetalleProducto )
                                        opc.alObtenerDetalleProducto( json );

                            } else {

                            }






                            break;
                        case 'confirm':

                                    if ( opc.alConfirmarPedido )
                                        opc.alConfirmarPedido( json );


                            break;
                        case 'confirm_mp':

                            if ( opc.alConfirmarPago )
                                opc.alConfirmarPago( json );


                            break;

                    }

                }
            });

        }

        /*,
        /*respuestaAccion: function ( json ){

            if ( json.state == 1 ){

                switch(json.data.accion){
                    case "add":
                        break;
                    case "del":
                        break;
                    case "clean":
                        break;
                    case "confirm":
                        break;
                }
                if (json.data.accion != "confirm" && json.data.registros){
                    CarritoCompra.mostrarDetalleCarrito(json.data.registros);
                }

            }

        },*/


    }
}();
