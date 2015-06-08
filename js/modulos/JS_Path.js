Path= function()
{
	return {
		
                entera: function(){
                    return location.href;
                },
                encode: function(sDato)
                {
                    return encodeURIComponent(sDato);
                },
                decode: function(sDato)
                {
                    return decodeURIComponent(sDato);
                },
                relativa: function(){
                    var ruta= location.href.split("/");
                    return ruta[ruta.length -1];
                },
                nombrePagina: function(){
                    var retorno= Path.relativa().split('?');
                    return retorno[0].split('#')[0];
                },
                id: function(){
                    var retorno= Path.relativa().split('#');
                    if(retorno.length> 1)
                    {
                        return retorno[retorno.length-1];
                    }
                    else
                        {
                            return '';
                        }
                },
                queryString: function(sNombreParam){
                    //[dami]aca hago un polimorfismo machazo
                    var retorno= {
                        toda: function(){
                            var retorno= Path.relativa().split('?');
                            return (retorno[1]? retorno[1]: '');
                        },
                        parametro:function(sNombre){
                            var retorno= {};
                            var e,
                                d = function (s)
                                {
                                    return decodeURIComponent(s.replace(/\+/g, " "));
                                },
                                q = window.location.search.substring(1),
                                r = /([^&=]+)=?([^&]*)/g;
                            while ((e = r.exec(q)))
                            {
                                retorno[d(e[1])] = d(e[2]);
                            }
                            return retorno[sNombre];
                        }
                    };

                    if(!sNombreParam)
                    {
                        return retorno.toda();
                    }
                    else
                        {
                            return retorno.parametro(sNombreParam);
                        }
                }
		
	
	}
}();