function mostrarDetalle(id){

    $.fancybox.open({
        type : 'iframe',
        href : 'ajax/pedidos.ajax.php?accion=md&id=' + id
    });

}

function mostrarEnvio(id){

$.fancybox.open({
        type    : 'iframe',
        width   : 800,
        height  : 300,
        href    : 'ajax/pedidos.ajax.php?accion=me&id=' + id
    });

}

