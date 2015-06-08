<?php
    require_once '../frm/init.php';
    require_once 'clases/Perfil.php';
    
    $params = $ioHelper->get('params');
    $fancy  = $ioHelper->get('fancy','0');
    $urlListado = "usuarios.php" . ($params ? "?" . $params : "");

    $id         = $ioHelper->get('id',0);
    $guid       = $ioHelper->get('encrypt',0);
    $reg        = new Ftl_UsuarioBO($id,$guid);

    
    if ($ioHelper->get('action') == 'edit')
    {
        Ftl_Header::setCharsetEncoding();
        $reg->setNombre     ( $ioHelper->get('txtNombre') );
        $reg->setApellido   ( $ioHelper->get('txtApellido') );
        $reg->setUsuario    ( $ioHelper->get('txtUsuario') );
        $reg->setClave      ( $ioHelper->get('txtClave') );
        $reg->setEmail      ( $ioHelper->get('txtEmail') );
        $reg->setEstado(1);
        
        
        if ($reg->getId() == 0)
            $reg->setFechaAlta  ( date('Y-m-d H-i-s') );

        //$reg->setFechaUltModificacion( date('Y-m-d H-i-s') );

        $respuesta = $reg->guardar();

        echo Ftl_JsonUtil::encode($respuesta);

        exit();
    }
    
    $page = new Ftl_PageBO();
    
    $page->setTitle(($id == 0 ? "Nuevo usuario":"Editar datos"));
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

        var campos = {
                fields: [
                    {
                        nombre:"txtNombre",
                        tipo: "",
                        mensajeVacio: "El nombre no puede estar vacío."
                    },
                    {
                        nombre:"txtApellido",
                        tipo: "",
                        mensajeVacio: "El apellido no puede estar vacío."
                    },
                    {
                        nombre:"txtUsuario",
                        tipo: "",
                        mensajeVacio: "El usuario no puede estar vacío."
                    },
                    {
                        nombre:"txtEmail",
                        tipo: "email",
                        mensajeErroneo: "El formato del email es incorrecto."
                    }
//                    },
//                    {
//                        nombre:"cmbPerfil",
//                        tipo: "",
//                        mensajeVacio: "Debes seleccionar el perfil."
//                    }

                ]

        };

        
        if ( $("#txtClave").length )
        {
            campos.fields.push ({
                        nombre:"txtClave",
                        tipo: "clave",
                        mensajeVacio: "Debes ingresar una nueva clave.",
                        mensajeErroneo: "La clave debe tener al menos 6 lestras."
                        
            });
        }

   
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

<!--<script src="js/jquery/jquery.selectbox-0.5_style_2.js" type="text/javascript"></script>-->
<!--div style="width: 260px; height: 50px; left: 542px; top: 175px;" class="progress_bar"><div id="progressbar"></div><p>Espere...</p></div-->

<form id="formulario" name="formulario" method="POST" enctype="multipart/form-data">
<table border="0" cellpadding="0" cellspacing="0"  id="id-form">
<tr>
        <th valign="top">Nombre:</th>
        <td><input id="txtNombre" name="txtNombre" type="text" class="ui-widget ui-widget-content ui-corner-all" value="<?php echo $ioHelper->output( $reg->getNombre() );?>" /></td>
</tr>
<tr>
        <th valign="top">Apellido:</th>
        <td>
            <input id="txtApellido" name="txtApellido" type="text" class="ui-widget ui-widget-content ui-corner-all" value="<?php echo $ioHelper->output( $reg->getApellido() );?>"/>
        </td>
</tr>
<tr>
        <th valign="top">Usuario:</th>
        <td>
            <input id="txtUsuario" name="txtUsuario" type="text" class="ui-widget ui-widget-content ui-corner-all" value="<?php echo $ioHelper->output( $reg->getUsuario() );?>"/>
        </td>
</tr>
<?php

    if ($reg->getId() == 0) { ?>
        <tr>
                <th valign="top">Clave:</th>
                <td>
                    <input id="txtClave" class="ui-widget ui-widget-content ui-corner-all" name="txtClave" type="text" value="<?php echo $ioHelper->output( $reg->getClave() );?>"/>
                </td>
        </tr>
<?php } ?>
<tr>
        <th valign="top">Email:</th>
        <td>
            <input id="txtEmail" class="ui-widget ui-widget-content ui-corner-all" name="txtEmail" type="text" value="<?php echo $ioHelper->output( $reg->getEmail() );?>"/>
        </td>
</tr>
<tr style="display: none;">
<th valign="top">Perfil:</th>
<td>
<select id="cmbPerfil" class="ui-widget ui-widget-content ui-corner-all" name="cmbPerfil" >
        <option value="">--</option>
        <?php

            foreach(Perfil::getLista() as $k => $v)
            {
                echo "<option value=\"$k\" " . ( $reg->getIdPerfil()  == $k ? "selected" : "") . ">$v</option>";
            }
        ?>
</select>
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