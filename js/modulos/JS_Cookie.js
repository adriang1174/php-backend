Cookie= function()
{
	return {
		
        setCookie: function(arrCookies, opciones)
        {
//            var options = { path: '/', expires: 10 };

            $(arrCookies).each(function(index){
                $.cookie(arrCookies[index].clave, arrCookies[index].valor);
            });
        },
		
	
	}
}();