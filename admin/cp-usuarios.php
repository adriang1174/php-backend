<?php
    require_once '../frm/init.php';

    $params = $ioHelper->get('params');
    $fancy  = $ioHelper->get('fancy','0');
    $urlListado = "usuarios.php" . ($params ? "?" . $params : "");

    $id         = $ioHelper->get('id',0);
    $guid       = $ioHelper->get('encrypt',false);
    
    $reg        = new Ftl_UsuarioBO($id,$guid);
    
    
    if ($ioHelper->get('action') == 'save')
    {
        
        $reg->setClave      ( $ioHelper->getEscaped('txtClave') );
        $respuesta = $reg->cambiarClave();
        echo Ftl_JsonUtil::encode($respuesta);

        exit();
    }
    
    $page = new Ftl_PageBO();
    $page->setTitle("Cambiar clave");
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
            url         : '<?php echo Ftl_Path::getFileName() . "?action=save&id=$id&encrypt=$guid";?>',
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
    var error = "";


    //var form = jqForm[0];


        var campos = {
                fields: [
                    {
                        nombre:"txtClave-txtClave2",
                        tipo: "clave",
                        mensajeVacio: "Debes ingresar una nueva clave.",
                        mensajeErroneo: "La clave debe tener al menos 6 lestras.",
                        mensajeDistintas: "Las claves ingresadas son distintas."
                    }
                ]

        };

    if(!$.fn.validar(campos))
    {
        UI.alert(campos.message,'<p>@</p>',{title:'Atención'});
		
        return false;
    }
    else{

	UI.showModalLoader();

        return true;

    }

}
</script>

		<!-- start id-form -->
                <form id="formulario" name="formulario" method="POST" enctype="multipart/form-data">
		<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
                    <tr>
                            <th valign="top">Nueva clave:</th>
                            <td><input id="txtClave" name="txtClave" type="text" class="inp-form" /></td>
                    </tr>
                    <tr>
                            <th valign="top">Repetir clave:</th>
                            <td>
                                <input id="txtClave2" name="txtClave2" type="text" class="inp-form" />
                            </td>
                    </tr>

                    <tr>
                            <th>&nbsp;</th>
                            <td valign="top" colspan="2" align="center">

                                    <button class="ui-button" id="btnSubmir" ui-icon="ui-icon-disk">Guardar</button>
                                    <button class="ui-button" id="btnReset" ui-icon="ui-icon-close" onclick="document.location = '<?php echo $urlListado;?>';return false;">Cancelar</button>

                            </td>
                    </tr>
                </table>
                </form>
	<!-- end id-form  -->
<?php
$page->showFoot(($fancy == 1 ? FALSE : TRUE));