var tr,trs;
var cant = 0;

$(function(){
    $('#btnChangeStatus1Rows').click(function (){
        
        cant = $('input[name=chkRow]:checked').length;
        if (cant > 0){
            
            var ids = [];
            $('input[name=chkRow]:checked').each(function() {
                ids.push($(this).val());
            });            
            
            aprobarFotos(ids.join(','));
            
            
        }else{
            alert('Seleccion las fotos.');
        }
        
       
    });
    $('#btnChangeStatus0Rows').click(function (){
        cant = $('input[name=chkRow]:checked').length;
        if (cant > 0){
            
            var ids = [];
            $('input[name=chkRow]:checked').each(function() {
                ids.push($(this).val());
            });            
            
            rechazarFotos(ids.join(','));
            
            
        }else{
            alert('Selecciona las fotos.');
        }
    });
   
});





function aprobarFotos(ids){
    if (confirm('Deséa aprobar las fotos seleccionadas?\n(Nota: Solo se aprobarán las fotos que tengan el filtro aplicado)')){
        UI.showModalLoader();
        JS.ajax.llamada({
           url: '../ajax/admin.php?oper=AF',
           data:{
               ids: ids
           },
           alFinalizar: function(r){
               location.reload();
           }
        });
    }
}
function rechazarFotos(ids){
    if (confirm('Deséa rechazar las fotos seleccionadas?')){
        UI.showModalLoader();
        JS.ajax.llamada({
           url: '../ajax/admin.php?oper=RF',
           data:{
               ids: ids
           },
           alFinalizar: function(r){
               location.reload();
           }
        });
    }
}

function download_original(id){
  window.open('download.php?id='+id);
}
function upload_editada(id){

  $('<a id=\'fancybox-frame\'></a>').fancybox({
    closeBtn: false,
    type : 'iframe',
    href : 'upload.php?&encrypt=0&id='+id+'&fancy=1',
    width:500,height:250,modal:1,
    afterClose : function() {
      location.reload();return;
    }
  }).trigger('click');


}