/*
 * VALIDADOR
 * creado por Leao
 *
 * tipo de campos soportados:
 * email, dni, fecha, radio, checkbox, grupoChecks, longMin, combo
 */
(function($){
    $.fn.validar = function(elements, options)
    {
        var $this = $(this);
        var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
        var fechaReg = /^\d{2}\/\d{2}\/\d{4}$/;
        var er = null;
        var mensajeSalida = [];
	
	var options = jQuery.extend({
		title: 'Atención',
		width: '321px',
		modal: true,
		resizable: false,
		buttons: {"Ok": function() {$(this).dialog("close");}}
	}, options);

        $.each(elements.fields, function(key,val)
        {
            var t = this.tipo;
            var campo = $('#'+this.nombre).val();
            
            switch (t)
            {

                case "hasClass":
                    
                    if(!$('#'+this.nombre).hasClass(this.clase))
                    {
                        mensajeSalida.push(this.mensajeErroneo);
                    }
                break;
                case "email":
                    campo = campo.trim();
                    if(campo == "" && this.mensajeVacio)
                    {
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    else if (!emailReg.test(campo))
                    {
                        mensajeSalida.push(this.mensajeErroneo);
                    }
                break;
                case "emailconfirm":
                    var mails = this.nombre.split("-");
                    
                    if (mails.length == 2)
                    {
                        mail  = $('#'+mails[0]).val().trim();
                        mail2 = $('#'+mails[1]).val().trim();

                        if (mail == '' && this.mensajeVacio)
                        {
                            mensajeSalida.push(this.mensajeVacio);
                        }
                        else if (!emailReg.test(mail) && this.mensajeErroneo)
                        {
                            mensajeSalida.push(this.mensajeErroneo);
                        }
                        else if ((mail != mail2)  && this.mensajeDistinto){
                            mensajeSalida.push(this.mensajeDistinto);
                        }
                    }



                        
                    break;

                case "grupoEmails":
                    $(this.nombre).each(
                        function(intIndex) {
                            if (!emailReg.test($('#'+this).val())) {
                                mensajeSalida.push(elements.fields[key].mensajeErroneo.replace("[index]",intIndex+1));
                            }
                        }
                        );
                    break;

                case "dni":
                    campo = campo.trim();
                    if(campo == "")
                    {
                        mensajeSalida.push(this.mensajeVacio);
                    }else if(campo.length < 7 || campo.length > 8){
                        mensajeSalida.push(this.mensajeErroneo);
                    }
                    break;
                case "rut":
                    campo = campo.trim();
                    if ( campo.length == 0 ){ 
                        mensajeSalida.push(this.mensajeVacio); 
                    }else
                        if ( campo.length < 8 ){ 
                            mensajeSalida.push(this.mensajeErroneo); 
                        }else{
                        
                            campo = campo.replace('-','')
                            campo = campo.replace(/\./g,'')

                            var suma = 0;
                            var caracteres = "1234567890kK";
                            var contador = 0;    
                            for (var i=0; i < campo.length; i++){
                                    u = campo.substring(i, i + 1);
                                    if (caracteres.indexOf(u) != -1)
                                    contador ++;
                            }
                            if ( contador==0 ){ 
                                mensajeSalida.push(this.mensajeErroneo); 
                            }else{

                                var rut = campo.substring(0,campo.length-1)
                                var drut = campo.substring( campo.length-1 )
                                var dvr = '0';
                                var mul = 2;

                                for (i= rut.length -1 ; i >= 0; i--) {
                                        suma = suma + rut.charAt(i) * mul
                                        if (mul == 7) 	mul = 2
                                                else	mul++
                                }
                                res = suma % 11
                                if (res==1)		dvr = 'k'
                                else if (res==0)        dvr = '0'
                                else {
                                        dvi = 11-res
                                        dvr = dvi + ""
                                }
                                if ( dvr != drut.toLowerCase() ) { 
                                    mensajeSalida.push(this.mensajeErroneo); 
                                } 

                                
                            }

                        
                        
                        
                        }
                            



                    break;
                case "fecha":
                    
                    var fecha = this.nombre.split("-");
                    var dia = $('#'+fecha[0] + " :selected").val() || $('#'+fecha[0]).val().trim();
                    var mes = $('#'+fecha[1] + " :selected").val()  || $('#'+fecha[1]).val().trim();
                    var anio = $('#'+fecha[2] + " :selected").val()  || $('#'+fecha[2]).val().trim();
					
					if (dia == '' && mes == '' && anio == '')
					{
						mensajeSalida.push(this.mensajeVacio);
					}
                    else 
						if(!_isDate(dia, mes, anio))
						{
							mensajeSalida.push(this.mensajeErroneo);
						}

                    break;
                case "edad":
                    
                    var fecha = this.nombre.split("-");
                    var dia = $('#'+fecha[0] + " :selected").val() || $('#'+fecha[0]).val().trim();
                    var mes = $('#'+fecha[1] + " :selected").val()  || $('#'+fecha[1]).val().trim();
                    var anio = $('#'+fecha[2] + " :selected").val()  || $('#'+fecha[2]).val().trim();

                    if(!_isDate(dia, mes, anio))
                    {
                        mensajeSalida.push(this.mensajeErroneo);
                    }else
                    {
                        var dNac = new Date(anio, mes-1, dia, 0, 0, 0, 0);
                        var edad = dNac.edad(new Date());
                        if (this.esMayorDe)
                        {
                            
                            if (edad < this.esMayorDe)
                                mensajeSalida.push(this.mensajeMenor);

                        }else
                            if (this.rango)
                            {
                                
                                if (edad < this.rango[0] || edad > this.rango[1])
                                    mensajeSalida.push(this.mensajeRango);

                            }
                    }

                    break;					
                case "radio":
                    if (!$("input[name="+ this.nombre + "]:radio").is(':checked')){
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    break;
                case "checkbox":
                    if (!$("#"+ this.nombre).is(':checked')){
                        mensajeSalida.push(this.mensajeVacio);
                    }

                    break;
                case "grupoChecks":
                    var sel = false;
                    var arrCheks = this.nombre.split('-');

                    $(arrCheks).each(
                        function( intIndex ){
                            if ($("#"+ arrCheks[intIndex]).is(':checked')){
                                sel = true;
                            }
                        }
                        );
                    if (sel == false){
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    break;
                case "longMin":
                    if ($("#"+ this.nombre).val().length < 3){
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    break;
                case "combo":
                    if(campo == "0" && $('#'+this.nombre).is(':visible'))
                    {
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    break;
                case "clave":
                    var claves = this.nombre.split("-");

                    if (claves.length == 2)
                    {
                        clave  = $('#'+claves[0]).val().trim();
                        clave2 = $('#'+claves[1]).val().trim();

                    }
                    else
                    {
                        clave  = $('#'+claves[0]).val().trim();
                        clave2  = $('#'+claves[0]).val().trim();
                    }


                    if (clave == '')
                    {
                        mensajeSalida.push(this.mensajeVacio);
                    }else
                        if(clave.length < 6)
                        {
                            mensajeSalida.push(this.mensajeErroneo);
                        }else
                        {
                            if(this.mensajeDistintas && clave != clave2)
                            {
                                mensajeSalida.push(this.mensajeDistintas);
                            }

                        }

                    break;

                case "custom":
                    if(!this.validacionCustom(campo))
                    {
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    break;
                case "fechaespdp":
                    
                    var fecha = $('#'+this.nombre).val().split('/');
                    var dia = (fecha[0]!==undefined?fecha[0]:'');
                    var mes = (fecha[1]!==undefined?fecha[1]:'');
                    var anio = (fecha[2]!==undefined?fecha[2]:'');
					
                    if (dia == '' && mes == '' && anio == '')
                    {
                            mensajeSalida.push(this.mensajeVacio);
                    }
                    else 
                        if(!_isDate(dia, mes, anio))
                        {
                                mensajeSalida.push(this.mensajeErroneo);
                        }else
                            {
                                var dNac = new Date(anio, mes-1, dia, 0, 0, 0, 0);
                                var edad = dNac.edad(new Date());
                                if (this.esMayorDe)
                                {

                                    if (edad < this.esMayorDe)
                                        mensajeSalida.push(this.mensajeMenor);

                                }else
                                    if (this.rango)
                                    {

                                        if (edad < this.rango[0] || edad > this.rango[1])
                                            mensajeSalida.push(this.mensajeRango);

                                    }
                            }                            

                    break;  
                case "fechaesptxt":
                    
                    campo = campo.trim();
                    if (campo.length == 0){
                        mensajeSalida.push(this.mensajeVacio);
                    }else{
                        
                        er = new RegExp(fechaReg);
                        
                        if (!campo.match(er)){
                            mensajeSalida.push(this.mensajeErroneo);
                        }else{

                            var fecha = campo.split('/');
                            var dia = (fecha[0]!==undefined?fecha[0]:'');
                            var mes = (fecha[1]!==undefined?fecha[1]:'');
                            var anio = (fecha[2]!==undefined?fecha[2]:'');

                            if (dia == '' && mes == '' && anio == '')
                            {
                                    mensajeSalida.push(this.mensajeVacio);
                            }
                            else 
                                    if(!_isDate(dia, mes, anio))
                                    {
                                            mensajeSalida.push(this.mensajeErroneo);
                                    }else
                                        {
                                            var dNac = new Date(anio, mes-1, dia, 0, 0, 0, 0);
                                            var edad = dNac.edad(new Date());
                                            if (this.esMayorDe)
                                            {

                                                if (edad < this.esMayorDe)
                                                    mensajeSalida.push(this.mensajeMenor);

                                            }else
                                                if (this.rango)
                                                {

                                                    if (edad < this.rango[0] || edad > this.rango[1])
                                                        mensajeSalida.push(this.mensajeRango);

                                                }
                                        } 



                        }
                        
                        
                    }
                    
                    

                    break;  
                case "telCarrefour":
                    
                    var telefonos = this.nombre.split("-");
                    var nro_tel_1 = $('#'+telefonos[0]).val().trim();
                    var nro_tel_2 = $('#'+telefonos[1]).val().trim();
                    
                    
                    if ( nro_tel_1 == '' && nro_tel_2 == ''){
                        mensajeSalida.push(this.mensajeVacio);
                    }else{
                        
                        if (nro_tel_1 != '' || nro_tel_2 != ''){
                            // Esto está OK
                            var listo = false;
                            if (nro_tel_1 != '' && (nro_tel_1.length < 8 || nro_tel_1.length > 12)){
                                mensajeSalida.push(this.mensajeErroneo);
                                listo = true;
                            }
                            if (!listo && (nro_tel_2 != '' && (nro_tel_2.length < 8 || nro_tel_2.length > 12))){
                                mensajeSalida.push(this.mensajeErroneo);
                            }
                        }else{
                            mensajeSalida.push(this.mensajeErroneo);
                        }
                        
                    }
                    
                    break;                    
                case "sucCarrefour":
                    
                    var datos_sucursales = this.nombre.split("-");
                    var prov = $('#'+datos_sucursales[0]).val().trim();
                    var ciudad = $('#'+datos_sucursales[1]).val().trim();
                    var sucursal = $('#'+datos_sucursales[2]).val().trim();
                    
                    if (prov == "" || ciudad == "" || sucursal == ""){
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    
                    break;                    
                default:
                    if(campo == "" || (this.predeterminado && campo.toLowerCase() == this.predeterminado.toLowerCase())/*&& $('#'+this.nombre).is(':visible')*/)
                    {
                        mensajeSalida.push(this.mensajeVacio);
                    }
                    else
                        if(this.igualA)
                        {
                            if(campo!= $('#'+ this.igualA).val())
                                mensajeSalida.push(this.mensajeIgualdad);
                        }
            }
        });

        if(mensajeSalida.length > 0)
        {
            elements.message = mensajeSalida;//$('<div>' + mensajeSalida + '</div>').dialog(options);
            return false;
        }else{
            return true;
        }

    };

    //Agrego el metodo edad al Objeto fecha
    Date.prototype.edad=function(at){
        var value = new Date(this.getTime());
        var age = at.getFullYear() - value.getFullYear();
        value = value.setFullYear(at.getFullYear());
        if (at < value) --age;
        return age;
    };


    function _isDate(dd,mm,yyyy){
        var d = new Date(mm + "/" + dd + "/" + yyyy);
        return d.getMonth() + 1 == mm && d.getDate() == dd && d.getFullYear() == yyyy;
    }

})(jQuery);