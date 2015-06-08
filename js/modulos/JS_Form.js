Form= function()
{
	return {

            soloNumeros: function(sComodin)
            {
                $(sComodin).keypress(
                    function(event)
                    {
                        var charCode = ((event.which) ? event.which : event.keyCode);
                        if (charCode > 31 && (charCode < 48 || charCode > 57))
                        {
                            return false;
                        }
                        else
                            {
                                return true;
                            }
                    }
                );
            },
            soloRUT: function(sComodin)
            {
                $(sComodin).keypress(
                    function(event)
                    {
                        var charCode = ((event.which) ? event.which : event.keyCode);
                        if ((charCode > 31 && charCode != 45) && (charCode < 48 || (charCode > 57 && (charCode != 75 && charCode != 107))))
                        {
                            return false;
                        }
                        else
                            {
                                return true;
                            }
                    }
                );
            },
            soloFechas: function(sComodin)
            {
                $(sComodin).keypress(
                    function(event)
                    {
                        var charCode = ((event.which) ? event.which : event.keyCode);
                        if (charCode > 31 && (charCode < 47 || charCode > 57))
                        {
                            return false;
                        }
                        else
                            {
                                return true;
                            }
                    }
                );
            },
            llenarCombosFecha: function(cmbDia,cmbMes,cmbAnio)
            {
                            $(cmbDia).append("<option selected value=''>--</option>");
                            $(cmbMes).append("<option selected value=''>--</option>");
                            $(cmbAnio).append("<option selected value=''>--</option>");
                            //dia
                            for(i=1;i<=31;i++)
                            {
                                    $(cmbDia).append(
                                            $('<option></option>').val(JS.data.strPad(i,2,'0','STR_PAD_LEFT')).html(i)
                                    );
                            }
                            //mes
                            for(i=1;i<=12;i++)
                            {
                                    $(cmbMes).append(
                                            $('<option></option>').val(JS.data.strPad(i,2,'0','STR_PAD_LEFT')).html(i)
                                    );
                            }
                            //anio
                            var fecha 	= new Date();
                            var anio 	= fecha.getFullYear();
                            for(i=anio;i>=1900;i--)
                            {
                                    $(cmbAnio).append(
                                            $('<option></option>').val(i).html(i)
                                    );
                            }


            },
             cargarCombo: function(objeto, valores, urlDestino, valorSeleccionado){
                    $.ajax({
                            url: urlDestino,
                            data: valores,
                            dataType: "json",
                            success: function(respuesta){
                                    $(objeto +' option').remove();
                                    
                                    $.each(respuesta, function(key, val) {
                                        $(objeto).append("<option value='"+val[0]+"'>"+val[1]+"</option>");
                                    });
                                    $('select'+objeto+' option[value='+valorSeleccionado+']').attr("selected","selected");

                            }
                    });
            }
		
	};
}();