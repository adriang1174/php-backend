<?php
    require_once '../frm/init.php';
    
    
    $params = $ioHelper->get('params');
    $fancy  = $ioHelper->get('fancy','0');
    $urlListado = "fotos.php" . ($params ? "?" . $params : "");

    $id         = $ioHelper->get('id',0);
    $guid       = $ioHelper->get('encrypt',0);
    $reg        = new Class_Foto($id);

    
    if ($ioHelper->get('action') == 'edit')
    {
        Ftl_Header::setCharsetEncoding();
        $respuesta = new Ftl_Response();

        //$reg->setFechaUltModificacion( date('Y-m-d H-i-s') );

            $arrGuardar = array();
            if ( isset($_FILES['fImagen']) && $_FILES['fImagen']['size'] > 0 ){
                $oImg   = new Ftl_Image();
                $oImg->fromUploadedFile('fImagen',PATH_UPLOADS);
                if ($oImg->isImage())
                {
                    $ext = $oImg->save($reg->getId().'_editada');
                    $arrGuardar["editada"]=$ext->data;
                    
                }
            }
            if (count($arrGuardar) > 0){
                $respuesta = $reg->guardar($arrGuardar);
            }
       




        echo Ftl_JsonUtil::encode($respuesta);

        exit();
    }
    
    $page = new Ftl_PageBO();
    
    $page->setTitle(($id == 0 ? "Subir Imagen editada":"Subir Imagen editada"));
    $page->loadSripts("ui,form");
    $page->checkSession();
    $page->showHeader(($fancy == 1 ? FALSE : TRUE));
    $page->showMenu(($fancy == 1 ? FALSE : TRUE));
    $page->showTop(($fancy == 1 ? FALSE : TRUE));
?>

<script language="javascript" type="text/javascript">

$(document).ready(function(){
    UI.estilarBotones();
    $('#formulario').ajaxForm({
            url         : '<?php echo Ftl_Path::getFileName() . "?action=edit&id=$id"?>',
            type        : 'POST',
            dataType    : 'json',
            beforeSubmit: validate,
            success     : hecho
    });
    $('#btnReset').click(function (){
        <?php
            if ($fancy == 1){
                echo "parent.$.fancybox.close();";
            }else{
                echo "JS.redirect('" .$urlListado ."');";
            }
        ?>
    });
});

extArray = new Array(".jpg", ".jpeg", ".png");  
function LimitAttach(fileA) { 
    allowSubmitA = false; 


    if(fileA&&fileA!=="") {
        while (fileA.indexOf("\\") != -1) 
            fileA = fileA.slice(fileA.indexOf("\\") + 1); 

        ext = fileA.slice(fileA.indexOf(".")).toLowerCase(); 

        for (var i = 0; i < extArray.length; i++) { 
            if (extArray[i] == ext) { allowSubmitA = true; break; } 
        } 
    } else {
        allowSubmitA = true;
    }


        if (allowSubmitA) { 
            return true;

        } else {
//                alert("Se permiten únicamente archivos con la extención:\n\n "  
//                    + (extArray.join("  ")) 
//                    + "\n\nPor favor, seleccione otro archivo e intente de nuevo."); 
            return false;
        }

} 
function hecho(data){
    
        UI.hideModalLoader();
    if (data){
            switch(data.state){
                case 1:
                    <?php 
                        if ($fancy == 1){
                            echo "parent.$.fancybox.close();";
                        }else{
                            echo "JS.redirect('" .$urlListado ."');";
                        }
                    ?>
                    break;
                case -1:
                    UI.alert('Ocurrieron errores durante el proceso.',{title:'Error'});
                    break;
                default:
                    UI.alert(data.message,{title:'Atención'});
                    break;

            }

        }
}



function validate(formData, jqForm, options) {
    var error = [];

    
    if ( $('#hImagen').val() == "" && $('#fImagen').val() == ""){
        error.push("Debe seleccionar una imagen.");
    }else{
        if (!LimitAttach($('#fImagen').val())){
            error.push("Revisa la imagen. Se permiten únicamente archivos con la extención: "+extArray.join("  "));
        }        
    }  

    if(error.length > 0)
    {
        UI.alert(error,'<p>@</p>',{title:'Atención'});
        return false;
    }
    else{

    UI.showModalLoader();
        return true;

    }
        
        
}
</script>

<!--<script src="js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>-->
<!--div style="width: 260px; height: 50px; left: 542px; top: 175px;" class="progress_bar"><div id="progressbar"></div><p>Espere...</p></div-->

<form id="formulario" name="formulario" method="POST" enctype="multipart/form-data">
<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
            <tr class="frm-item">
                <td class="left">Imagen<br><sub style="color:red;"></sub></td>
                <td class="right">
                    <input type="hidden" id="hImagen" name="hImagen" value=""/>
                    <input type="file" id="fImagen" name="fImagen" class="input-text ui-widget ui-corner-all"/>          
                </td>
            </tr>

<tr>

<td valign="top" colspan="2" align="center">

        <button class="ui-button" id="btnSubmir" ui-icon="ui-icon-disk">Guardar</button>
        <button class="ui-button" id="btnReset" ui-icon="ui-icon-close" onclick="document.location = '<?php echo $urlListado;?>';return false;">Cancelar</button>
       
</td>
</tr>
</table>
</form>
<?php
$page->showFoot(($fancy == 1 ? FALSE : TRUE));