<?php
    require_once '../frm/init.php';
    

    Ftl_Header::setCharsetEncoding();
    Ftl_Header::setNoCache();

    switch ($ioHelper->get('action'))
    {
        case 'login':

            $usuario    = isset($_REQUEST['txtUsuario']) ? $_REQUEST['txtUsuario'] : "";
            $clave      = isset($_REQUEST['txtPass']) ? $_REQUEST['txtPass'] : "";

            if ($usuario != "" && $clave != "")
            {
                $respuesta = Ftl_SessionBO::login($usuario, $clave);
                if ($respuesta->state == 1)
                {
                    $respuesta->data = null;
                }
                echo Ftl_JsonUtil::encode($respuesta);
            }
            exit();
            break;
        case 'recovery':

            exit();
            break;
    }

    $oSession = new Ftl_SessionBO();
    if ($oSession->isLogged())
        Ftl_Redirect::toPage ('index.php');
    
    
    
    
    $page = new Ftl_PageBO();
    $page->setTitle("Administrador");
    $page->loadSripts("ui,form");
    $page->setCacheable(false);
    $page->showHeader(false);

    $page->showTop();
?>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
        
        $(document).pngFix( );
        UI.estilarBotones();
        $('#formulario').ajaxForm({
                url         : 'login.php',
                type        : 'POST',
                dataType    : 'json',
		beforeSubmit: validate,
		success     : hecho
	});
});

function hecho(data){

        
	if (data){
            switch(data.state){
                case 1:
                JS.redirect('index.php');
                break;
                default:
                        UI.hideModalLoader();
                        UI.alert(data.message,{title:'Atención'});
                    break;
            }

        }
}

function validate(formData, jqForm, options) {
    var error = "";
    
    var form = jqForm[0];

        var campos = {
                fields: [
                    {
                        nombre:"txtUsuario",
                        tipo: "",
                        mensajeVacio: "Por favor, ingresá tu usuario.",
                        mensajeErroneo: ""
                    },
                    {
                        nombre:"txtPass",
                        tipo: "",
                        mensajeVacio: "Por favor, ingresá tu password.",
                        mensajeErroneo: ""
                    }
                ]

        };

    if(!$.fn.validar(campos))
    {

        UI.alert(campos.message,'<p>@</p>',{title:'Atención'});
    	
	
        return false;
    }
    else
    {
        UI.showModalLoader();
        return true;
    }

}

</script>
 <form id="formulario">
     <input type="hidden" name="action" id="action" value="login"/>
<!-- Start: login-holder -->
<div id="login-holder">


	
	<div class="clear"></div>
	

	<div class="ui-state-default" style="padding: 30px 20px; width: 250px;">
		<table id="tblLogin" border="0" cellpadding="0" cellspacing="0">
		<tr>
                    
                    <td>
                        <label>Usuario</label>
                        <input id="txtUsuario" name="txtUsuario" type="text" class="input-text ui-widget ui-corner-all"/>
                    </td>
		</tr>
		<tr>
                    
                    <td>
                        <label>Clave</label>
                        <input id="txtPass" name="txtPass" type="password" value=""  onfocus="this.value=''" class="input-text ui-widget ui-corner-all"/>
                    </td>
		</tr>
		<tr>
		
			<td valign="top">

                        </td>
		</tr>
		<tr>
			<td><input type="submit" class="ui-button" id="btnSubmir" ui-icon="ui-icon-disk" value="Acceder"/></td>
		</tr>
		</table>
	</div>
 	<!--  end login-inner -->
	<div class="clear"></div>
	<!--a href="" class="forgot-pwd">Olvidó su clave?</a-->


</div>
<!-- End: login-holder -->
 </form>

<?php
    $page->showFoot();
