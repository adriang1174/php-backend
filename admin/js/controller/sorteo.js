$(function(){


    //UI.showModalLoader();

	$('#btn_sortear').click(function(){

    if (confirm('Deséa realizar el sorteo?')){
        UI.showModalLoader();
        JS.ajax.llamada({
           url: '../ajax/admin.php?oper=SORTEAR',
           alFinalizar: function(r){
				location.reload();
           }
        });
    }		


	});


});